<?php

namespace App\Controller\espaceAdmin;

use App\Manager\UserManager;
use App\Repository\UserRepository;
use App\Repository\UserSubscriptionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * UserSubscriptionController constructor.
     *
     * @param UserRepository $repos
     */
    public function __construct(UserRepository $repos, PaginatorInterface $paginator)
    {
        $this->repos = $repos;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="app_espace_admin_user_subscriber_list")
     *
     * @return Response
     */
    public function userSubscriptionList(Request $request)
    {
        $result = $this->repos->findAll();

        $subscribers = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $subscribers->setSortableTemplate('shared/sortable_link.html.twig');

        return $this->render('espace_admin/subscriber/list.html.twig', [
            'title' => 'Liste des abonnés',
            'subscribers' => $subscribers,
        ]);
    }

    public function userDetailSubscription()
    {

    }
}
