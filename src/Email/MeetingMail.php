<?php


namespace App\Email;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Constants\EmailMeetingConstant;

class MeetingMail extends AbstractController
{
    private $mailer;

    public function __construct(Email $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailToParticipant($emailParticipant, $urlMeeting)
    {
        $subject = "Invitation à participer à une réunion sur Hiboo";
        $to = [
            $emailParticipant,
        ];
        $template = $this->renderView('emails/meeting/sendMail.html.twig', [
            'message' => EmailMeetingConstant::_MESSAGE_TO_SEND_,
            'urlMeeting' => $urlMeeting,
        ]);

        return $this->mailer->createEmail($to, $subject, $template);
    }
}
