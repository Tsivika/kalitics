<?php


namespace App\Email\Handler;


use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SwiftMailerHandler implements MessageHandlerInterface
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * SwiftMailerHandler constructor.
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @param Swift_Message $email
     */
    public function __invoke(Swift_Message $email)
    {
        if ($_ENV['APP_ENV'] == 'dev') $email->setSubject('[DEV] ' . $email->getSubject());
        if ($_ENV['APP_ENV'] == 'preprod') $email->setSubject('[PREPROD] ' . $email->getSubject());
        $this->mailer->send($email);
    }
}