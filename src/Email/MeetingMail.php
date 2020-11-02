<?php


namespace App\Email;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MeetingMail extends AbstractController
{
    private $mailer;

    public function __construct(Email $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailToParticipant($data)
    {
        $subject = "Invitation à participer à une réunion sur Hiboo";
        $to = [
            'contactdiaryko@gmail.com',
        ];
        $template = $this->renderView('emails/meeting/sendMail.html.twig', ['message' => $data['message']]);

        return $this->mailer->createEmail($to, $subject, $template);
    }
}
