<?php

namespace App\Controller\FrontEnd;

use App\Form\ContactType;
use App\Manager\GuideManager;
use App\Manager\PartnerManager;
use App\Manager\SubscriptionManager;
use App\Manager\TestimonialManager;
use App\Manager\VideoGuideManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="app_homePage")
     *
     * @return Response
     */
    public function index(Request $request, SubscriptionManager $subscriptionManager, TestimonialManager $testimonialManager, PartnerManager $partnerManager, VideoGuideManager $guideManager)
    {
        $form = $this->createForm(ContactType::class);
        $subscriptions = $subscriptionManager->findAll();
        $testimonials = $testimonialManager->findAll();
        $partners = $partnerManager->findAll();
        $guides = $guideManager->findAll();

        return $this->render("frontend/home/index.html.twig", [
            'form' => $form->createView(),
            'subscriptions' => $subscriptions,
            'testimonials' => $testimonials,
            'partners' => $partners,
            'guides' => $guides,
        ]);
    }

    /**
     * @Route("/set_cookie",
     *     name="app_set_cookie",
     *     options={"expose"=true},
     *     methods={"GET"}
     *     )
     *
     * @return bool|string
     */
    public function setCookieAction()
    {

        if (isset($_COOKIE["iboo_cookie_check"])) {
        } else {
            setcookie("iboo_cookie_check", "iboo.live", time() + 365*24*3600, "/", null, false, true);
        }

        return new JsonResponse( [
            'success' => true,
        ]);
    }
}
