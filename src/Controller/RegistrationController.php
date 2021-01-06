<?php

namespace App\Controller;

use App\Entity\Meeting;
use App\Entity\Partner;
use App\Entity\Subscription;
use App\Entity\User;
use App\Form\Handler\AccountHandler;
use App\Form\Handler\MeetingHandler;
use App\Form\Handler\RegisterHandler;
use App\Form\Handler\RegisterUserMeetingHandler;
use App\Form\MeetingParticipantType;
use App\Form\RegistrationFormType;
use App\Form\UserAccountType;
use App\Manager\AccountManager;
use App\Manager\MeetingManager;
use App\Manager\ParameterManager;
use App\Manager\PartnerManager;
use App\Manager\RegisterManager;
use App\Manager\SubscriptionManager;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use App\Services\StripePayement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

/**
 * Class RegistrationController
 * @package App\Controller
 */
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
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var ContainerBagInterface
     */
    private $params;

    /**
     * @var mixed
     */
    private $urlBbb;

    /**
     * @var mixed
     */
    private $secretBbb;

    /**
     * @var
     */
    private $partners;

    /**
     * RegistrationController constructor.
     *
     * @param EmailVerifier         $emailVerifier
     * @param RegisterManager       $em
     * @param SessionInterface      $session
     * @param RouterInterface       $router
     * @param ContainerBagInterface $params
     * @param PartnerManager        $partnerManager
     */
    public function __construct(EmailVerifier $emailVerifier, RegisterManager $em, SessionInterface $session, RouterInterface $router, ContainerBagInterface $params, PartnerManager $partnerManager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->em = $em;
        $this->session = $session;
        $this->router = $router;
        $this->params = $params;
        $this->urlBbb = $this->params->get('app.bbb_server_base_url');
        $this->secretBbb = $this->params->get('app.bbb_secret');
        $this->partners = $partnerManager->findAll();
    }

    /**
     * @Route("/put_session_create_user/{email}",
     *     name="app_pre_register_user",
     *     options={"expose"=true},
     *     methods={"get"})
     *
     * @param $email
     *
     * @return JsonResponse
     */
    public function putSessionCreateUser($email)
    {
        $this->session->set('preCreateUser', $email);

        return new JsonResponse( [
            'success' => true,
            'urlRedirect' => $this->router->generate('app_register')
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     * @Route("/register/{subscription}", name="app_register")
     *
     * @param Request                       $request
     * @param UserPasswordEncoderInterface  $passwordEncoder
     * @param GuardAuthenticatorHandler     $guardHandler
     * @param LoginFormAuthenticator        $authenticator
     *
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, SubscriptionManager $subscriptionManager, $subscription = null): Response
    {
        $pathPrePayment= $this->session->get('prePayment');
        $preUser = $this->session->get('preCreateUser') ?? '';
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $handler = new RegisterHandler($form, $request, $passwordEncoder, $guardHandler, $authenticator, $user, $this->emailVerifier, $this->em, $subscriptionManager, $pathPrePayment);
        if ($handler->process()) {
            if ($pathPrePayment !== null) {
                return $this->forward('App\Controller\RegistrationController::paymentSubscriber', [
                    'id' => substr($pathPrePayment, -1)
                ]);
            }

            /*return $this->forward('App\Controller\RegistrationController::registerUserMeeting', [
                'hideMenuRegister' => 'register',
            ]);*/

            return $this->forward('App\Controller\RegistrationController::registerConfirmation');
        }

        $response = $this->render('registration/register.html.twig', [
            'registrationForm' => $handler->getForm()->createView(),
            'preUser' => $preUser,
            'partners' => $this->partners,
        ]);

        if ($request->isXmlHttpRequest()){
            return new JsonResponse([
                'html' => $response->getContent()
            ]);
        }

        return $response;
    }

    /**
     * @Route("/register-confirmation", name="app_user_registration_confirmation")
     *
     * @return Response
     */
    public function registerConfirmation(Request $request)
    {
        $response = $this->render('registration/confirmation_register.html.twig', [
            'mail_user' => $this->getUser()->getEmail(),
            'title' => 'Confirmation de votre compte',
            'partners' => $this->partners,
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
     * @param RouterInterface $router
     * @param ParameterManager $paramManager
     * @param MeetingManager $meetingManager
     *
     * @param string $hideMenuRegister
     * @return JsonResponse|Response
     *
     * @throws \Exception
     */
    public function registerUserMeeting(Request $request, RouterInterface $router, ParameterManager $paramManager, MeetingManager $meetingManager, string $hideMenuRegister = 'register')
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

        $handlerUserMeeting = new RegisterUserMeetingHandler($formUserMeeting, $request, $user, $this->em);
        $formUserMeeting->handleRequest($request);
        if ($formUserMeeting->isSubmitted() && $formUserMeeting->isValid()) {
            $handlerUserMeeting->onSuccess();
            $meetingManager->createMeeting($request, $paramManager, $user);
            return $this->forward('App\Controller\RegistrationController::registerUserRunMeeting', [
                'hideMenuRegister' => 'register',
            ]);
        }

        $response = $this->render('registration/register_user_meeting.html.twig', [
            'form' => $formUserMeeting->createView(),
            'userSubscription' => $userSubscription,
            'partners' => $this->partners,
            'hideMenuRegister' => $hideMenuRegister
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
     * @param Request $request
     * @param MeetingManager $manager
     *
     * @param string $hideMenuRegister
     * @return JsonResponse|Response
     */
    public function registerUserRunMeeting(Request $request, MeetingManager $manager, string $hideMenuRegister = 'register')
    {
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $user = $this->getUser();
        if (!$user) {
            throw new Exception("Vous n'avez pas accès à cette page");
        }
        $meeting = $manager->getUserLastMeeting($this->getUser());
        $identifiant = $meeting->getIdentifiant();
        
        //Current participant
        $participant = $meeting->getParticipant($user->getUsername());

        $response = $this->render('registration/register_user_run_meeting.html.twig', [
            'link' => $baseurl.'/reunion/'.$identifiant.'/'.$participant->getId(),
            'partners' => $this->partners,
            'hideMenuRegister' => $hideMenuRegister
        ]);

        if ($request->isXmlHttpRequest()){
            return new JsonResponse([
                'html' => $response->getContent()
            ]);
        }

        return $response;
    }

    /**
     * @Route("/payment_subscriber/{id}", name="app_register_payment_subscriber")
     *
     * @param Request               $request
     * @param Subscription          $subscription
     * @param SubscriptionManager   $subscriptionManager
     * @param StripePayement        $stripe
     * @param AccountManager        $accountManager
     *
     * @return RedirectResponse|Response
     */
    public function paymentSubscriber(Request $request, Subscription $subscription, SubscriptionManager $subscriptionManager, StripePayement $stripe, AccountManager $accountManager)
    {
        $form = $this->createForm(UserAccountType::class, $this->getUser());
        $subPaying = $subscriptionManager->getPayingSubscription();
        $handler = new AccountHandler($form, $request, $accountManager, $stripe);
        if ($handler->process()) {
            return $this->redirectToRoute('app_espace_client_subscription_list');
        }

        $response = $this->render('payment/index.html.twig', [
            'subscripbiontChoice' => $subscription,
            'subscriptionPaying' => $subPaying,
            'user' => $this->getUser(),
            'form' => $form->createView(),
            'type' => $subscription->getId()
        ]);

        if ($request->isXmlHttpRequest()){
            return new JsonResponse([
                'html' => $response->getContent()
            ]);
        }

        return $response;
    }

    /**
     * @Route("/re_send_email_confirmation", name="app_re_send_email_confirmation")
     *
     * @param EmailVerifier $emailVerifier
     *
     * @return RedirectResponse
     */
    public function reSendEmailConfirmation(EmailVerifier $emailVerifier)
    {
        $this->em->sendEmailConfirmation($this->getUser(), $emailVerifier);

        return $this->redirectToRoute('app_user_registration_confirmation');
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

        $this->addFlash('success', 'Votre adresse e-mail a été vérifiée.');

        return $this->redirectToRoute('register_user_meeting', [
            'hideMenuRegister' => 'register',
        ]);
    }

    /**
     * @Route("/test_confirmation", name="test_confirmation")
     * @return Response
     */
    public function test()
    {
        return $this->render('registration/confirmation_register.html.twig', [
            'mail_user' => 'tsivika@gmail.com',
            'title' => 'Confirmation de votre compte',
            'partners' => $this->partners,
        ]);
    }
}
