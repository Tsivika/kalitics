<?php

namespace App\Controller\espaceAdmin;

use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Repository\UserSubscriptionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/espace_admin/subscriber")
 *
 * Class UserSubscriptionController
 *
 * @package App\Controller\espaceAdmin
 */
class UserSubscriptionController extends AbstractController
{
    /**
     * @var UserSubscriptionRepository
     */
    private $repos;

    /**
     * UserSubscriptionController constructor.
     *
     * @param UserRepository $repos
     */
    public function __construct(UserRepository $repos)
    {
        $this->repos = $repos;
    }

    /**
     * @Route("/", name="app_espace_admin_user_subscriber_list")
     *
     * @return Response
     */
    public function userSubscriptionList()
    {
        $subscribers = $this->repos->findAll();

        return $this->render('espace_admin/subscriber/list.html.twig', [
            'title' => 'Liste des abonnés',
            'subscribers' => $subscribers,
        ]);
    }
}
