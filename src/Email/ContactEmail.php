<?php


namespace App\Email;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ContactEmail extends AbstractController
{
    private $mailer;

    public function __construct(Email $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailMessage($data)
    {
        $subject = $data['name']. " demande plus d'information";
        $to = [
            'contactdiaryko@gmail.com',
        ];
        $template = $this->renderView('emails/contact/sendMail.html.twig', ['message' => $data['message']]);

        return $this->mailer->createEmail($to, $subject, $template);
    }
}