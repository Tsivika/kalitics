<?php

namespace App\Controller;

use App\Manager\MeetingManager;
use App\Manager\ParameterManager;
use phpDocumentor\Reflection\Types\True_;
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
     * MeetingLaunchController constructor.
     * @param MeetingManager $meetingManager
     * @param ParameterManager $parameterManager
     */
    public function __construct(MeetingManager $meetingManager, ParameterManager $parameterManager)
    {
        $this->meetingManager = $meetingManager;
        $this->parameterManager = $parameterManager;
    }

    /**
     * @Route("/reunion/{identifiant}", name="app_launch_meeting_fr")
     * @Route("/meeting/{identifiant}", name="app_launch_meeting_en")
     *
     * @param Request $request
     * @param $identifiant
     * @return RedirectResponse
     * @throws \Exception
     */
    public function meetingRedirectUrl(Request $request, $identifiant)
    {
        $meeting = $this->meetingManager->meetingByIdentifiant($identifiant);
        $today = new \DateTime("now");
        $dateMeeting = $meeting->getCreatedAt();
        $diff = $today->format('Y-m-d') > $dateMeeting->format('Y-m-d');

        if ($diff) {
            $this->addFlash('error', 'La date de la réunion est déjà passée.');
            $this->redirectToRoute('app_espace_client_meeting_list');
        } else {
            $url = $this->meetingManager
                ->generateLinkMeet($meeting, $this->parameterManager, $request);

            return $this->redirect($url);
        }

        return $this->redirectToRoute('app_espace_client_meeting_list');
    }
}
