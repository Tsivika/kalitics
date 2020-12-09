<?php

namespace App\Controller\FrontEnd;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\Handler\ContactHandler;
use App\Manager\ContactManager;
use App\Manager\PartnerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponseAlias;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface as TransportExceptionInterfaceAlias;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\SendEmailService;

class ContactController extends AbstractController
{
    /**
     * @var ContactManager
     */
    private $em;
    /**
     * @var PartnerManager
     */
    private $partnerManager;
    /**
     * @var SendEmailService
     */
    private $emailService;

    /**
     * ContactController constructor.
     *
     * @param ContactManager   $em
     * @param PartnerManager   $partnerManager
     * @param SendEmailService $emailService
     */
    public function __construct(ContactManager $em, PartnerManager $partnerManager, SendEmailService $emailService)
    {
        $this->em = $em;
        $this->partnerManager = $partnerManager;
    }

    /**
     * @Route("/contact", name="app_contact")
     *
     * @param Request $request
     *
     * @return RedirectResponseAlias|ResponseAlias
     *
     * @throws TransportExceptionInterfaceAlias
     */
    public function contact(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $handler = new ContactHandler($form, $request, $this->em);
        
        if ($handler->process()) {
            $this->addFlash('success', 'Demande de contact prise en compte. Un email sera envoyé au responsable.');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render("frontend/contact/index.html.twig", [
            'form' => $form->createView(),
            'partners' => $this->partnerManager->findAll(),
            'title' => 'Contacter Iboo'
        ]);
    }
}
