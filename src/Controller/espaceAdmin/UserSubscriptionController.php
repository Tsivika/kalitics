<?php

namespace App\Controller\espaceAdmin;

use App\Entity\Meeting;
use App\Entity\User;
use App\Form\Handler\admin\SendEmailToUserHandler;
use App\Form\SendEmailToUserType;
use App\Manager\MeetingManager;
use App\Manager\UserManager;
use App\Manager\UserSubscriptionManager;
use App\Repository\UserRepository;
use App\Repository\UserSubscriptionRepository;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponseAlias;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/espace_admin/subscriber", name="app_espace_admin_user_subscriber")
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
     * @Route("/", name="_list")
     *
     * @return Response
     */
    public function userSubscriptionList(Request $request)
    {
        $result = $this->repos->getUserNotDeleted();

        $subscribers = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $subscribers->setSortableTemplate('shared/sortable_link_colored.html.twig');

        return $this->render('espace_admin/subscriber/list.html.twig', [
            'title' => 'Liste des abonnés',
            'subscribers' => $subscribers,
        ]);
    }

    /**
     * @Route("/detail/{id}", name="_detail")
     */
    public function userDetailSubscription(User $user, MeetingManager $meetingManager)
    {
        return $this->render('espace_admin/subscriber/detail.html.twig', [
            'user' => $user,
            'detailSubscription' => $user->getUserSubscriptions(),
            'meetings' => $meetingManager->getStatMeetingPerUser($user->getId()),
            'title' => 'Détail client'
        ]);
    }

    /**
     * @Route("/send_email_to_user/{id}", name="_send_email")
     *
     * @param Request $request
     * @param User    $user
     *
     * @return RedirectResponseAlias|Response
     */
    public function sendEmailToUser(Request $request, User $user, UserSubscriptionManager $manager)
    {
        $form = $this->createForm(SendEmailToUserType::class);
        $handler = new SendEmailToUserHandler($form, $request, $manager);
        if ($handler->process()) {
            return $this->redirectToRoute('app_espace_admin_user_subscriber_detail',[
                'id' => $user->getId(),
            ]);
        }

        return $this->render('espace_admin/subscriber/sendMailToUser.html.twig', [
            'title' => 'Envoyer un email à un utilisateur',
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
