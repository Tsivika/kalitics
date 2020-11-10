<?php


namespace App\Manager;


use App\Entity\UserSubscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface as TransportExceptionInterfaceAlias;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Services\SendEmailService;

/**
 * Class UserSubscriptionManager
 * @package App\Manager
 */
class UserSubscriptionManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var SendEmailService
     */
    private $emailService;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, SendEmailService $emailService)
    {
        parent::__construct($em, UserSubscription::class, $validator);
        $this->em = $em;
        $this->emailService = $emailService;
    }

    /**
     * @param $data
     * 
     * @throws TransportExceptionInterfaceAlias
     */
    public function sendEmail($data)
    {
        $template = 'emails/user/sendMail.html.twig';
        $context = [
            'subject' => $data['subject'],
            'message' => $data['message']
        ];
        $this->emailService->sendEmail($_ENV['CONTACT_MAIL'], $data['emailUser'], $data['subject'], $template, $context) ;
    }
}
