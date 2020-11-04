<?php

namespace App\Controller\FrontEnd;

use App\Email\SwiftMailerEmail;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\Handler\ContactHandler;
use App\Manager\ContactManager;
use App\Manager\PartnerManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
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
     * @return ResponseAlias
     */
    public function contact(Request $request, \Swift_Mailer $mailer)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $handler = new ContactHandler($form, $request, $this->em);
        if ($handler->process()) {

            $transport = (new \Swift_SmtpTransport('smtp.sendgrid.net', 587))
                ->setUsername('contact@hiboo.live')
                ->setPassword('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');

            $mailered = new \Swift_Mailer($transport);

            $message = (new \Swift_Message())
                ->setSubject('Here should be a subject')
                ->setFrom(['contact@hiboo.live'])
                ->setTo(['tsivika@gmail.com' => 'nouveau mail '])
                ->setCc([
                    'contactdiaryko@gmail.com' => 'Product manager'
                ]);

            $message->setBody('<html><body><p>Welcome to at home</p></body></html>','text/html');
            $mailered->send($message);

            

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
