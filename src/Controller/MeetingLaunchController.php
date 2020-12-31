<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Manager\MeetingManager;
use App\Manager\ParameterManager;
use App\Manager\ParticipantManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * MeetingLaunchController constructor.
     * @param MeetingManager     $meetingManager
     * @param ParameterManager   $parameterManager
     * @param ParticipantManager $participantManager
     */
    public function __construct(MeetingManager $meetingManager, ParameterManager $parameterManager, ParticipantManager $participantManager)
    {
        $this->meetingManager = $meetingManager;
        $this->parameterManager = $parameterManager;
        $this->participantManager = $participantManager;
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
     * @return RedirectResponse
     */
    public function meetingRedirectUrl(Request $request, $identifiant, $participant)
    {
        $meeting = $this->meetingManager->meetingByIdentifiant($identifiant);
        $participant = $this->participantManager->getById($participant);
        $today = new \DateTime("now");
        $dateMeeting = $meeting->getDate();
        $diff = $today->format('Y-m-d') > $dateMeeting->format('Y-m-d');
        
        if (!$participant instanceof Participant) {
            $this->addFlash('error', 'Participant introuvable.');
            return $this->redirectToRoute('app_espace_client_meeting_list');
        }

        if ($diff) {
            $this->addFlash('error', 'La date de la réunion est déjà passée.');
            $this->redirectToRoute('app_espace_client_meeting_list');
        } else {
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
}
