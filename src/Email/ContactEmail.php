<?php


namespace App\Email;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class ContactEmail
 *
 * @package App\Email
 */
class ContactEmail extends AbstractController
{
    /**
     * @var Email
     */
    private $mailer;

    /**
     * ContactEmail constructor.
     *
     * @param Email $mailer
     */
    public function __construct(Email $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    public function sendEmailMessage($data)
    {
        $subject = $data['name']. " demande plus d'information";
        $to = [
            $this->getParameter('app.contact.mail'),
        ];
        $template = $this->renderView('emails/contact/sendMail.html.twig', ['message' => $data['message']]);

        return $this->mailer->createEmail($to, $subject, $template);
    }
}
