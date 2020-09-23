<?php


namespace App\Controller\espaceAdmin;

use App\Manager\MeetingManager;
use App\Manager\SubscriptionManager;
use App\Manager\UserManager;
use App\Manager\UserSubscriptionManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/espace_admin")
 *
 * Class DashboardController
 *
 * @package App\Controller\espaceAdmin
 */
class DashboardController extends AbstractController
{
    /**
     * @var UserManager
     */
    private $userSubEm;

    /**
     * @var MeetingManager
     */
    private $meetingEm;

    /**
     * @var SubscriptionManager
     */
    private $subEm;

    /**
     * DashboardController constructor.
     *
     * @param UserManager           $userSubEm
     * @param MeetingManager        $meetingEm
     * @param UserSubscriptionManager   $subEm
     */
    public function __construct(UserManager $userSubEm, MeetingManager $meetingEm, UserSubscriptionManager $subEm)
    {
        $this->userSubEm = $userSubEm;
        $this->meetingEm = $meetingEm;
        $this->subEm = $subEm;
    }


    /**
     * @Route("/", name="app_espace_admin_dashbord")
     *
     * @return Response
     */
    public function dashbord()
    {
        $subscribers = $this->userSubEm->statUserSubscriber();
        $meetings = $this->meetingEm->getStatMeeting();
        $userSubscriptions = $this->userSubEm->getStatUserSubscription();

        return $this->render('espace_admin/dashbord/index.html.twig', [
            'title' => 'Tableau de bord',
            'subscribers' => $subscribers,
            'userSubscriptions' => $userSubscriptions,
            'meetings' => $meetings
        ]);
    }
}