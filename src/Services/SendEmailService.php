<?php


namespace App\Services;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface as TransportExceptionInterfaceAlias;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * Class SendEmailService
 *
 * @package App\Services
 */
class SendEmailService
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * SendEmailService constructor.
     *
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $from
     * @param $to
     * @param $subject
     * @param $template
     * @param $context
     *
     * @throws TransportExceptionInterfaceAlias
     */
    public function sendEmail($from, $to, $subject, $template, $context)
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to(new Address($to))
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context)
        ;

        $this->mailer->send($email);
    }
}
