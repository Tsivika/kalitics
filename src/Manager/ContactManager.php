<?php


namespace App\Manager;

use App\Email\ContactEmail;
use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $mailer;

    private $bus;

    /**
     * CategoryManager constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, ContactEmail $mailer, MessageBusInterface $bus)
    {
        parent::__construct($em, Contact::class, $validator);
        $this->em = $em;
        $this->mailer = $mailer;
        $this->bus = $bus;
    }

    public function saveContact($data)
    {
        $contact = new Contact();
        $contact->setName($data['name'])
            ->setEmail($data['email'])
            ->setMessage($data['message'])
            ;

        $this->saveOrUpdate($contact);
    }

    public function sendEmail($data)
    {
        return $this->bus->dispatch($this->mailer->sendEmailMessage($data));
    }
}