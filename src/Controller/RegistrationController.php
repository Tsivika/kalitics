<?php

namespace App\Controller;

use App\Entity\Meeting;
use App\Entity\User;
use App\Form\Handler\RegisterHandler;
use App\Form\Handler\RegisterUserMeetingHandler;
use App\Form\MeetingParticipantType;
use App\Form\MeetingType;
use App\Form\RegistrationFormType;
use App\Manager\RegisterManager;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    /**
     * @var EmailVerifier
     */
    private $emailVerifier;

    private $em;

    /**
     * RegistrationController constructor.
     *
     * @param EmailVerifier $emailVerifier
     */
    public function __construct(EmailVerifier $emailVerifier, RegisterManager $em)
    {
        $this->emailVerifier = $emailVerifier;
        $this->em = $em;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $handler = new RegisterHandler($form, $request, $passwordEncoder, $guardHandler, $authenticator, $user, $this->emailVerifier, $this->em);
        if ($handler->process()) {
            return new Response('Ajouter');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/register-user-meeting", name="register_user_meeting")
     *
     * @param Request $request
     */
    public function registerUserMeeting(Request $request)
    {
        $user = $this->em->find(1);
        $meeting = new Meeting();
        $formUserMeeting = $this->createForm(MeetingParticipantType::class, $meeting);
        $handler = new RegisterUserMeetingHandler($formUserMeeting, $request, $user, $this->em);
        if ($handler->process()) {
            return new Response('Ajouter');
        }

        return $this->render('registration/register_user_meeting.html.twig', [
            'form' => $formUserMeeting->createView(),
        ]);
    }


    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
