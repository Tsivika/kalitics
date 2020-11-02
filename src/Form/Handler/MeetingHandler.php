<?php


namespace App\Form\Handler;

use App\Entity\User;
use App\Manager\ParameterManager;
use DateTime;
use App\Manager\MeetingManager;
use phpDocumentor\Reflection\Types\This;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

use App\bbb\src\BigBlueButton;
use App\bbb\src\Parameters\CreateMeetingParameters;
use App\bbb\src\Parameters\JoinMeetingParameters;
use App\bbb\src\Parameters\EndMeetingParameters;

/**
 * Class MeetingHandler
 * @package App\Form\Handler
 */
class MeetingHandler extends Handler
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * MeetingHandler constructor.
     *
     * @param FormInterface  $form
     * @param Request        $request
     * @param User           $user
     * @param MeetingManager $em
     */
    public function __construct(FormInterface $form, Request $request, User $user, MeetingManager $em, RouterInterface $router)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->user = $user;
        $this->router = $router;
    }

    /**
     * @return bool|mixed
     */
    function onSuccess()
    {
        $uuid = Uuid::uuid4();
        $idRandom = explode('-',$uuid->toString());
        $identifiant = array_reverse($idRandom);

        $meeting = $this->form->getData();
        $meeting->setIdentifiant($identifiant[0]);
        $meeting->setUser($this->user);
        $meeting->setLink('');

        $this->em->save($meeting);

        return true;
    }

    /**
     * @param ParameterManager $paramManager
     *
     * @return string
     *
     * @throws \Exception
     */
    public function createMeeting(ParameterManager $paramManager, $urlBbb, $secretBbb)
    {
        $meetingUser = $this->em->getUserLastMeeting($this->user);
        $paramUser = $paramManager->getParamUser($this->user);
        $baseurl = $this->request->getScheme() . '://' . $this->request->getHttpHost() . $this->request->getBasePath();
        if (empty($paramUser) ) {
            $paramManager->setDefaultParam($this->user);
            $paramUser = $paramManager->getParamUser($this->user);
        }
        $duration = ($meetingUser->getDurationH()*60) + $meetingUser->getDurationM();
        $pwdModerator = $this->passwordModerator($meetingUser->getPassword());

        $bbb = new BigBlueButton($urlBbb, $secretBbb);
        $createMeetingParams = new CreateMeetingParameters($meetingUser->getId(), $meetingUser->getSubject());
        $createMeetingParams->setAttendeePassword($meetingUser->getPassword());
        $createMeetingParams->setModeratorPassword($pwdModerator);
        $createMeetingParams->setDuration($duration);
        $createMeetingParams->setLogoutUrl($baseurl);
        $createMeetingParams->setMaxParticipants(count($meetingUser->getParticipants()));

        if ($paramUser->getRecordAuto()) {
            $createMeetingParams->setRecord(true);
            $createMeetingParams->setAllowStartStopRecording(true);
            $createMeetingParams->setAutoStartRecording(true);
            $createMeetingParams->setMeetingId($meetingUser->getId());
            $createMeetingParams->setMeetingName($meetingUser->getSubject());
        }

        if ($paramUser->getSoundParticipant()) {
            $createMeetingParams->setMuteOnStart(true);
        }

        if ($paramUser->getMessagePublic()) {
            $createMeetingParams->setLockSettingsDisablePublicChat(false);
        }

        if ($paramUser->getAnnotationParticipant()) {
            $createMeetingParams->setLockSettingsDisableNote(false);
        }

        if ($paramUser->getBoardParticipant()) {
            $createMeetingParams->setLockSettingsLockedLayout(false);
        }

        $response = $bbb->createMeeting($createMeetingParams);

        if ($response->getReturnCode() == 'FAILED') {
            return 'Impossible de créer la réunion! veuillez contacter notre administrateur.';
        } else {
            $url = $this->joinMeeting($meetingUser, 'participant', $urlBbb, $secretBbb);

            $meetingUser->setLink($url);
            $meetingUser->setPasswordModerator($pwdModerator);
            $this->em->save($meetingUser);
            $this->em->sendMailToParticipants($meetingUser->getParticipants());

            return $url;
        }
    }

    /**
     * @param $meetingUser
     * @param $mode
     *
     * @return string
     *
     * @throws \Exception
     */
    public function joinMeeting($meetingUser, $mode, $urlBbb, $secretBbb)
    {
        $username = 'Hiboo participant';
        $password = $meetingUser->getPassword();
        if ($mode == 'moderator')
        {
            $username = $this->user->getFirstname();
            $password = $this->passwordModerator($meetingUser->getPassword());
        }

        $bbb = new BigBlueButton($urlBbb, $secretBbb);

        $joinMeetingParams = new JoinMeetingParameters($meetingUser->getId(), $username, $password);
        $joinMeetingParams->setRedirect(true);

        $url = $bbb->getJoinMeetingURL($joinMeetingParams);

        return $url;
    }

    /**
     * @param $pwd
     *
     * @return string
     *
     * @throws \Exception
     */
    public function passwordModerator($pwd)
    {
        $rand = random_int(34, 2200);
        $mpw = md5($rand.$pwd);

        return $mpw;
    }


    /**
     * @param $meeting
     *
     * @return bool|string
     */
    public function restriction($meeting)
    {
        $participantSubscription = $this->user->getSubscriptionUser()->getNumberParticipant();
        $durationSubscription = $this->user->getSubscriptionUser()->getDurationMeeting();
        $durationInput = $meeting->getDurationM();
        $participantInput = count($meeting->getParticipants());
        $subscription = $this->user->getSubscriptionUser()->getMode();

        if ($subscription === 'free') {
            if ($durationInput > (int) $durationSubscription) {
                $error = 'La durée de votre réunion est de : ' . $durationSubscription;

                return $error;
            }
            if ($participantInput > (int) $participantSubscription) {
                $error = 'Vous avez atteint le nombre maximum de participants, qui est de: ' .$participantSubscription;

                return $error;
            }
        }

        return true;
    }
}
