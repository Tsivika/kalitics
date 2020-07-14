<?php


namespace App\Controller\espaceClient;

use App\Manager\MeetingManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espace_client")
 * Class MeetingController
 * @package App\Controller\espaceClient
 */
class MeetingController extends AbstractController
{
    private $em;

    public function __construct(MeetingManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/meeting", name="app_espace_client_meeting_list")
     */
    public function meetingList()
    {
        $meetings = $this->em->getUserMeetingList($this->getUser());

        return $this->render('espace_client/meeting/list.html.twig', [
            'meetings' => $meetings,
        ]);
    }
}