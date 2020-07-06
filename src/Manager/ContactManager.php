<?php


namespace App\Manager;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CategoryManager constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Contact::class, $validator);
        $this->em = $em;
    }

    public function saveContact(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $contact = new Contact();
        $contact->setName($data['name'])
            ->setEmail($data['email'])
            ->setMessage($data['message'])
            ;

        $this->saveOrUpdate($contact);
    }
}