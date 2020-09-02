<?php

namespace App\Controller;

use App\Entity\Meeting;
use App\Entity\User;
use App\Form\Handler\RegisterHandler;
use App\Form\Handler\RegisterUserMeetingHandler;
use App\Form\MeetingParticipantType;
use App\Form\RegistrationFormType;
use App\Manager\MeetingManager;
use App\Manager\RegisterManager;
use App\Manager\SubscriptionManager;
use App\Manager\UserManager;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    /**
     * @var RegisterManager
     */
    private $em;

    /**
     * RegistrationController constructor.
     * @param EmailVerifier   $emailVerifier
     * @param RegisterManager $em
     */
    public function __construct(EmailVerifier $emailVerifier, RegisterManager $em)
    {
        $this->emailVerifier = $emailVerifier;
        $this->em = $em;
    }

    /**
     * @Route("/register", name="app_register")
     *
     * @param Request                       $request
     * @param UserPasswordEncoderInterface  $passwordEncoder
     * @param GuardAuthenticatorHandler     $guardHandler
     * @param LoginFormAuthenticator        $authenticator
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, SubscriptionManager $subscriptionManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $handler = new RegisterHandler($form, $request, $passwordEncoder, $guardHandler, $authenticator, $user, $this->emailVerifier, $this->em, $subscriptionManager);
        if ($handler->process()) {
            return $this->forward('App\Controller\RegistrationController::registerUserMeeting');
        }

        $response = $this->render('registration/register.html.twig', [
            'registrationForm' => $handler->getForm()->createView(),
        ]);

        if ($request->isXmlHttpRequest()){
            return new JsonResponse([
                'html' => $response->getContent()
            ]);
        }

        return $response;
    }

    /**
     * @Route("/register-user-meeting", name="register_user_meeting")
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function registerUserMeeting(Request $request)
    {
        $user = $this->getUser();
        $userSubscription = $user->getSubscriptionUser();
        if (!$user) {
            throw new Exception("Vous n'avez pas accès à cette page");
        }
        $meeting = new Meeting();
        $formUserMeeting = $this->createForm(MeetingParticipantType::class, $meeting, [
            'action' => $this->generateUrl('register_user_meeting')
        ]);
        $handler = new RegisterUserMeetingHandler($formUserMeeting, $request, $user, $this->em);
        if ($handler->process()) {
            return $this->forward('App\Controller\RegistrationController::registerUserRunMeeting');
        }

        $response = $this->render('registration/register_user_meeting.html.twig', [
            'form' => $formUserMeeting->createView(),
            'userSubscription' => $userSubscription,
        ]);

        if ($request->isXmlHttpRequest()){
            return new JsonResponse([
                'html' => $response->getContent()
            ]);
        }

        return $response;
    }

    /**
     * @Route("/register-user-run-meeting", name="register_user_run_meeting")
     *
     * @param Request        $request
     * @param MeetingManager $manager
     *
     * @return JsonResponse|Response
     */
    public function registerUserRunMeeting(Request $request, MeetingManager $manager)
    {
        $user = $this->getUser();
        if (!$user) {
            throw new Exception("Vous n'avez pas accès à cette page");
        }
        $meeting = $manager->getUserLastMeeting($this->getUser());
        $link = $meeting->getLink();

        $response = $this->render('registration/register_user_run_meeting.html.twig', [
            'link' => $link,
        ]);

        if ($request->isXmlHttpRequest()){
            return new JsonResponse([
                'html' => $response->getContent()
            ]);
        }

        return $response;
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     *
     * @param Request $request
     *
     * @return Response
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
