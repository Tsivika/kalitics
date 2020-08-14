<?php

namespace App\Controller\espaceClient;

use App\Entity\Subscription;
use App\Form\Handler\AccountHandler;
use App\Form\UserAccountType;
use App\Manager\SubscriptionManager;
use App\Manager\UserManager;
use App\Repository\CodePromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account")
 *
 * Class AccountController
 * @package App\Controller\espaceClient
 */
class AccountController extends AbstractController
{
    /**
     * @var UserManager
     */
    private $em;

    /**
     * ProfilController constructor.
     * @param UserManager $em
     */
    public function __construct(UserManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/subscription_choice/{id}",
     *     name="app_espace_client_profil_subscription_choice",
     *     options={"expose"=true},
     *     methods={"get"})
     *
     */
    public function userSubscriptionChoice(Subscription $subscription, SubscriptionManager $subscriptionManager)
    {
        $userSubscription = $this->em->userSubscriptionChoice($this->getUser(), $subscription);
        $subscriptions = $subscriptionManager->findAll();

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_client/subscription/list_ajax.html.twig', [
                'subscriptions' => $subscriptions,
                'userSubscription' => $userSubscription,
            ]),
            'body' => "<p>Changement d'abonnement pris en compte.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }

    /**
     * @Route("/subscription_deactive",
     *     name="app_espace_client_profil_subscription_deactive",
     *     options={"expose"=true},
     *     methods={"get"})
     *
     * @param SubscriptionManager $subscriptionManager
     *
     * @return JsonResponse
     */
    public function userSubscriptionDeactive(SubscriptionManager $subscriptionManager)
    {
        $userSubscription = $this->em->userSubscriptionDeactive($this->getUser(), $subscriptionManager);
        $subscriptions = $subscriptionManager->findAll();

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_client/subscription/list_ajax.html.twig', [
                'subscriptions' => $subscriptions,
                'userSubscription' => $userSubscription,
            ]),
            'body' => "<p>Votre abonnement est basculé automatiquement vers un abonnement gratuit.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }

    /**
     * @Route("/subscription_pre_payment/{id}",
     *     options={"expose"=true},
     *     name="app_espace_client_profil_subscription_pre_payment",
     *     methods={"post", "get"})
     *
     * @param Request               $request
     * @param Subscription          $subscription
     * @param SubscriptionManager   $subscriptionManager
     *
     * @return Response
     */
    public function userSubscriptionPrePayment(Request $request, Subscription $subscription, SubscriptionManager $subscriptionManager)
    {
        $form = $this->createForm(UserAccountType::class, $this->getUser());
        $subPaying = $subscriptionManager->getPayingSubscription();
        $handler = new AccountHandler($form, $request, $this->em);
        if ($handler->process()) {
            dd('ato');
        }

        return $this->render('payment/index.html.twig', [
            'subscripbiontChoice' => $subscription,
            'subscriptionPaying' => $subPaying,
            'user' => $this->getUser(),
            'form' => $form->createView(),
            'type' => $subscription->getId()
        ]);
    }

    /**
     * @Route("/code-promo/",
     *     options={"expose"=true},
     *     name="app_espace_client_profil_code_promo",
     *     methods={"post"})
     *
     * @param Request               $request
     * @param CodePromoRepository   $repos
     *
     * @return JsonResponse
     */
    public function codePromo(Request $request, CodePromoRepository $repos)
    {
        $data = json_decode($request->getContent(), true);
        $code = $data['code'];

        if (!$code) {
            return new JsonResponse(['result' => false]);
        }

        $codePromo = $repos->findOneBy(
            ['code' => $code]
        );

        if (null === $codePromo) {
            return new JsonResponse(['result' => false]);
        }

        return new JsonResponse([
            'result' => true,
            'reduction' => $codePromo->getReduction()
        ]);
    }
}
