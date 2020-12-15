<?php


namespace App\Controller;


use App\Manager\ParticipantManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetingNotificationCronController extends AbstractController
{
    private $em;

    public function __construct(ParticipantManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/notification_cron", name="app_notification_cron")
     */
    public function notificationCron()
    {
        $this->em->notifyParticipants();

        return new Response('Notification OK');
    }
}