<?php

namespace App\Controller\FrontEnd;

use App\Form\ContactType;
use App\Manager\SubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(Request $request, SubscriptionManager $subscriptionManager)
    {
        $form = $this->createForm(ContactType::class);
        $subscriptions = $subscriptionManager->findAll();

        return $this->render("frontend/home/index.html.twig", [
            'form' => $form->createView(),
            'subscriptions' => $subscriptions,
        ]);
    }
}
