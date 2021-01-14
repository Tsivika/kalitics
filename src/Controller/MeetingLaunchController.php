<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\CheckMeetingPasswordType;
use App\Manager\MeetingManager;
use App\Manager\ParameterManager;
use App\Manager\ParticipantManager;
use App\Manager\PartnerManager;
use App\Model\PasswordModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MeetingLaunchController
 * @package App\Controller
 */
class MeetingLaunchController extends AbstractController
{
    /**
     * @var MeetingManager
     */
    private $meetingManager;
    
    /**
     * @var ParameterManager
     */
    private $parameterManager;
    /**
     * @var ParticipantManager
     */
    private $participantManager;
    /**
     * @var SessionInterface
     */

    private $session;
    /**
     * @var
     */
    private $partners;

    /**
     * MeetingLaunchController constructor.
     * @param MeetingManager     $meetingManager
     * @param ParameterManager   $parameterManager
     * @param ParticipantManager $participantManager
     * @param SessionInterface   $session
     */
    public function __construct(
        MeetingManager $meetingManager,
        ParameterManager $parameterManager,
        ParticipantManager $participantManager,
        SessionInterface $session,
        PartnerManager $partnerManager
    ) {
        $this->meetingManager = $meetingManager;
        $this->parameterManager = $parameterManager;
        $this->participantManager = $participantManager;
        $this->session = $session;
        $this->partners = $partnerManager->findAll();
    }

    /**
     * @Route("/reunion/{identifiant}/{participant}", name="app_launch_meeting_fr")
     * @Route("/meeting/{identifiant}/{participant}", name="app_launch_meeting_en")
     *
     * @param Request $request
     * @param $identifiant
     * @param $participant
     *
     * @throws \Exception
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function meetingRedirectUrl(Request $request, $identifiant, $participant)
    {
        $meeting = $this->meetingManager->meetingByIdentifiant($identifiant);
        $participant = $this->participantManager->getById($participant);
        $today = new \DateTime("now");
        $dateMeeting = $meeting->getDate();
        $diff = $today->format('Y-m-d') > $dateMeeting->format('Y-m-d');
        
        $meetingPassword = $this->session->get('meetingPassword', null);
        
        if (!$meetingPassword || $meetingPassword !== $meeting->getPassword()) {
            $meetingFormPassword = new PasswordModel();
            $formUserMeeting = $this->createForm(CheckMeetingPasswordType::class, $meetingFormPassword);
            
            $formUserMeeting->handleRequest($request);
            
            if ($formUserMeeting->isSubmitted()) {
                if ($meetingFormPassword->getPassword() !== $meeting->getPassword()) {
                    $formUserMeeting->addError(new FormError('Mot de passe incorrect !'));
                }
                
                if ($formUserMeeting->isValid()) {
                    $this->session->set('meetingPassword', $meetingFormPassword->getPassword());
                    
                    return $this->redirectToRoute(
                        'app_launch_meeting_fr',
                        [
                            'identifiant' => $identifiant,
                            'participant' => $participant->getId(),
                        ]
                    );
                }
            }
            
            return $this->render('meeting/password_meeting.html.twig', [
                'form' => $formUserMeeting->createView(),
                'partners' => $this->partners,
            ]);
        }
        
        if (!$participant instanceof Participant) {
            $this->addFlash('error', 'Participant introuvable.');
            return $this->redirectToRoute('app_espace_client_meeting_list');
        }

        if ($diff) {
            $this->addFlash('error', 'La date de la réunion est déjà passée.');
            $this->redirectToRoute('app_espace_client_meeting_list');
        } else {
            if ($meeting->getUser()->getSubscriptionUser()->getMode() == 'free') {
                $expireMeeting = $this->meetingManager->getInfoMeetingBeforeRunning($meeting, $participant);
                if ($expireMeeting['expire'] === 2) {

                    return $this->render('meeting/expired_meeting.html.twig', [
                        'post' => $expireMeeting['post'],
                        'partners' => $this->partners,
                    ]);
                }
            }

            $url = $this->meetingManager->generateLinkMeet(
                $meeting,
                $this->parameterManager,
                $request,
                $participant
            );

            return $this->redirect($url);
        }

        return $this->redirectToRoute('app_espace_client_meeting_list');
    }

    /**
     * @Route("/expired-meeting", name="expiredMeeting")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function expireMeeting()
    {
        return $this->render('meeting/expired_meeting.html.twig', [
            'partners' => $this->partners,
        ]);
    }
}
