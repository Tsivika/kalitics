<?php


namespace App\Controller\espaceClient;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\SubscriptionManager;
use App\Entity\Subscription;

/**
 * @Route("/espace_client/subscription")
 *
 * Class SubscriptionController
 * @package App\Controller\espaceClient
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
     * @Route("/", name="app_espace_client_subscription_list")
     */
    public function subscriptionList()
    {
        $subscriptions = $this->em->findAll();
        $user = $this->getUser();
        $userSubscription = $user->getSubscriptionUser();

        return $this->render('espace_client/subscription/list.html.twig', [
            'title' => 'Liste des abonnements',
            'subscriptions' => $subscriptions,
            'userSubscription' => $userSubscription,
        ]);
    }
}
