<?php

namespace App\Controller\FrontEnd;

use App\Manager\ContactManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/send_message_contact",
     *  name="app_send_message_contact",
     *  options={"expose" = true},
     *  methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function sendMessageContact(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->em->saveContact($data);
        $this->em->sendEmail($data);

        $msg = 'Merci de nous avoir contacter. Votre message est envoyé au responsable.';

        return new JsonResponse([
            'msg' => $msg,
            'success' => true,
        ]);
    }
}
