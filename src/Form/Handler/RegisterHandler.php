<?php


namespace App\Form\Handler;

use App\Constants\EmailMeetingConstant;
use App\Entity\User;
use App\Manager\RegisterManager;
use App\Manager\SubscriptionManager;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class RegisterHandler extends Handler
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var GuardAuthenticatorHandler
     */
    private $guardHandler;

    /**
     * @var LoginFormAuthenticator
     */
    private $authenticator;

    /**
     * @var User
     */
    private $user;

    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    private $subscriptionManager;
    /**
     * @var RegisterManager
     */
    protected $em;

    /**
     * RegisterHandler constructor.
     *
     * @param FormInterface                 $form
     * @param Request                       $request
     * @param UserPasswordEncoderInterface  $passwordEncoder
     * @param GuardAuthenticatorHandler     $guardHandler
     * @param LoginFormAuthenticator        $authenticator
     * @param User                          $user
     * @param EmailVerifier                 $emailVerifier
     * @param RegisterManager               $em
     */
    public function __construct(FormInterface $form, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, User $user, EmailVerifier $emailVerifier, RegisterManager $em, SubscriptionManager $subscriptionManager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->passwordEncoder = $passwordEncoder;
        $this->guardHandler = $guardHandler;
        $this->authenticator = $authenticator;
        $this->user = $user;
        $this->emailVerifier = $emailVerifier;
        $this->em = $em;
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * @return mixed|\Symfony\Component\HttpFoundation\Response|null
     */
    function onSuccess()
    {
        $this->user->setPassword(
            $this->passwordEncoder->encodePassword(
                $this->user,
                $this->form->get('password')->getData()
            )
        );
        $this->user->setLanguage('fr');
        $this->user->setSubscriptionUser($this->subscriptionManager->getFreeSubscription());
        $this->em->save($this->user);

        // generate a signed url and email it to the user
        $emailUser = $this->user->getEmail();
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $this->user,
            (new TemplatedEmail())
                ->from(new Address($_ENV['CONTACT_MAIL'], 'Iboo la visio profeessionnelle'))
                ->to($emailUser)
                ->subject('Veuillez confirmer votre e-mail')
                ->htmlTemplate('emails/registration/confirmation_email.html.twig')
                ->context([
                    'user_email' => $this->user->getEmail(),
                    'signature' => EmailMeetingConstant::_SIGNATURE_,
                ])
        );

        // TODO  send an email

        return $this->guardHandler->authenticateUserAndHandleSuccess(
            $this->user,
            $this->request,
            $this->authenticator,
            'main'
        );
    }
}
