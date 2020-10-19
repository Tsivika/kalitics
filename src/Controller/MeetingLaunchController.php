<?php


namespace App\Controller;


use App\Manager\MeetingManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    private $em;

    /**
     * MeetingLaunchController constructor.
     *
     * @param MeetingManager $em
     */
    public function __construct(MeetingManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/reunion/{identifiant}", name="app_launch_meeting_fr")
     * @Route("/meeting/{identifiant}", name="app_launch_meeting_en")
     *
     * @param $identifiant
     *
     * @return RedirectResponse
     */
    public function meetingRedirectUrl($identifiant)
    {
        $meeting = $this->em->meetingByIdentifiant($identifiant);

        return $this->redirect($meeting->getLink());
    }
}