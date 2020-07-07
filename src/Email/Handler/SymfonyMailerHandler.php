<?php


namespace App\Email\Handler;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SymfonyMailerHandler implements MessageHandlerInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * SymfonyMailerHandler constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param TemplatedEmail $email
     */
    public function __invoke(TemplatedEmail $email)
    {
        try {
            if ($_ENV['APP_ENV'] == 'dev') $email->subject('[DEV] '. $email->getSubject());
            if ($_ENV['APP_ENV'] == 'preprod') $email->subject('[PREPROD] '. $email->getSubject());

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
        }
    }

}