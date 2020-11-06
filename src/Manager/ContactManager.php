<?php


namespace App\Manager;

use App\Email\ContactEmail;
use App\Entity\Contact;
use App\Services\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface as TransportExceptionInterfaceAlias;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ContactManager
 * @package App\Manager
 */
class ContactManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ContactEmail
     */
    private $mailer;

    /**
     * @var MessageBusInterface
     */
    private $bus;
    /**
     * @var SendEmailService
     */
    private $emailService;

    /**
     * ContactManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     * @param SendEmailService       $emailService
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, SendEmailService $emailService)
    {
        parent::__construct($em, Contact::class, $validator);
        $this->em = $em;
        $this->emailService = $emailService;
    }

    /**
     * @param $data
     */
    public function saveContact($data)
    {
        $contact = new Contact();
        $contact->setName($data['name'])
            ->setEmail($data['email'])
            ->setMessage($data['message'])
            ;

        $this->saveOrUpdate($contact);
    }

    /**
     * @param $data
     * @throws TransportExceptionInterfaceAlias
     */
    public function sendEmail($data)
    {
        $template = 'emails/contact/sendMail.html.twig';
        $context = [
            'name' => $data->getName(),
            'address_email' => $data->getEmail(),
            'message' => $data->getMessage()
        ];
        $this->emailService->sendEmail($_ENV['CONTACT_MAIL'], $_ENV['RECEIVER_CONTACT_MAIL'], 'Hiboo: Demande de contact client', $template, $context) ;
    }
}
