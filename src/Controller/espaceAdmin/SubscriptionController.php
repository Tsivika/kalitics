<?php


namespace App\Controller\espaceAdmin;


use App\Entity\Subscription;
use App\Form\Handler\admin\SubscriptionAdminHandler;
use App\Form\SubscriptionAdminType;
use App\Manager\SubscriptionManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/espace_admin/subscription")
 *
 * Class SubscriptionController
 *
 * @package App\Controller\espaceAdmin
 */
class SubscriptionController extends AbstractController
{
    /**
     * @var SubscriptionManager
     */
    private $em;

    /**
     * SubscriptionController constructor.
     *
     * @param SubscriptionManager $em
     */
    public function __construct(SubscriptionManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_espace_admin_subscription_list")
     * @return Response
     */
    public function subscriptionList()
    {
        $subscriptions = $this->em->findAll();
        return $this->render('espace_admin/subscription/list.html.twig',
            [
                'title' => 'Liste des abonnements',
                'subscriptions' => $subscriptions,
            ]);
    }

    /**
     * @Route("/add", name="app_espace_admin_subscription_add")
     * @Route("/edit/{id}", name="app_espace_admin_subscription_edit")
     * @param Request $request
     *
     * @return Response
     */
    public function sucriptionAdd(Request $request, Subscription $subscription = null)
    {
        $subscriptionEntity = $subscription ?? new Subscription();
        $form = $this->createForm(SubscriptionAdminType::class, $subscription);
        $mode = $subscription ?? false;
        $title = $subscription ? 'Modifier abonnement' : 'Ajout nouvel abonnement';
        $handler = new SubscriptionAdminHandler($form, $request, $this->em);
        if ($handler->process()) {
            return $this->redirectToRoute('app_espace_admin_subscription_list');
        }

        return $this->render('espace_admin/subscription/add.html.twig', [
            'title' => $title,
            'form' => $form->createView(),
            'mode' => $mode,
        ]);
    }

    /**
     * @Route("/delete/{id}",
     *     name="app_espace_admin_subscription_delete",
     *     options={"expose"=true},
     *     methods={"get"})
     *
     * @param Subscription $subscription
     *
     * @return JsonResponse
     */
    public function subscriptionDelete(Subscription $subscription)
    {
        $this->em->delete($subscription);
        $subscriptions = $this->em->findAll();

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_admin/subscription/liste_ajax.html.twig', [
                'subscriptions' => $subscriptions,
            ]),
            'body' => 'Abonnement supprimé',
            'footer' => '',
            'success' => true,
        ]) ;
    }
}
