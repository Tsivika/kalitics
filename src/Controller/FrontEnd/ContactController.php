<?php

namespace App\Controller\FrontEnd;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\Handler\ContactHandler;
use App\Manager\ContactManager;
use App\Manager\PartnerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

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
     * ContactController constructor.
     *
     * @param ContactManager $em
     */
    public function __construct(ContactManager $em, PartnerManager $partnerManager)
    {
        $this->em = $em;
        $this->partnerManager = $partnerManager;
    }

    /**
     * @Route("/contact", name="app_contact")
     *
     * @param Request $request
     * @param MailerInterface $mailer
     * @return ResponseAlias
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $handler = new ContactHandler($form, $request, $this->em);
        
        if ($handler->process()) {
            $email = (new Email())
                ->from('contact@hiboo.live')
                ->to('test+test+test@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
    
            $mailer->send($email);

            $this->addFlash('success', 'Demande de contact prise en compte. Un email sera envoyé au responsable.');
            
            return $this->redirectToRoute('app_contact');
        }

        return $this->render("frontend/contact/index.html.twig", [
            'form' => $form->createView(),
            'partners' => $this->partnerManager->findAll(),
        ]);
    }

    /**
     * @Route("/send_message_contact",
     *  name="app_send_message_contact",
     *  options={"expose" = true},
     *  methods={"post", "get"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function sendMessageContact(Request $request)
    {
//        dump($request->getContent());
        $data = json_decode($request->getContent(), true);
//        dd($data);
        $this->em->saveContact($data);
        $this->em->sendEmail($data);

        $msg = 'Merci de nous avoir contacter. Votre message est envoyé au responsable.';

        return new JsonResponse([
            'msg' => $msg,
            'success' => true,
        ]);
    }
}
