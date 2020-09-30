<?php

namespace App\Controller\FrontEnd;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\Handler\ContactHandler;
use App\Manager\ContactManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @var ContactManager
     */
    private $em;

    /**
     * ContactController constructor.
     *
     * @param ContactManager $em
     */
    public function __construct(ContactManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/contact", name="app_contact")
     *
     * @param Request $request
     * @return ResponseAlias
     */
    public function contact(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $handler = new ContactHandler($form, $request, $this->em);
        if ($handler->process()) {
            $this->addFlash('success', 'Demande de contact pris en compte. Un email sera envoyer au responsable.');
            return $this->redirectToRoute('app_contact');
        }

        return $this->render("frontend/contact/index.html.twig", [
            'form' => $form->createView(),
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
