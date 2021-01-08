<?php


namespace App\Manager;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use App\Constants\EmailMeetingConstant;


class RegisterManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * RegisterManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserManager $userManager
    ) {
        parent::__construct($em, User::class, $validator);
        $this->em = $em;
        $this->userManager = $userManager;
    }

    /**
     * Send email of confirmation
     */
    public function sendEmailConfirmation(User $user, EmailVerifier $emailVerifier)
    {
        $emailUser = $user->getEmail();
        $emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address($_ENV['CONTACT_MAIL'], 'Iboo la visio sécurisée'))
                ->to($emailUser)
                ->subject('Veuillez confirmer votre e-mail')
                ->htmlTemplate('emails/registration/confirmation_email.html.twig')
                ->context([
                    'user_email' => $emailUser,
                    'signature' => EmailMeetingConstant::_SIGNATURE_,
                ])
        );
    }

    /*public function deleteUserNotVerified()
    {
        $user = $this->userManager->deleteUserNotVerified();
        dd($user);
    }*/
}
