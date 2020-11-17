<?php


namespace App\Manager;

use App\Constants\EmailMeetingConstant;
use App\Entity\Meeting;
use App\Entity\User;
use App\Services\SendEmailService;
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class MeetingManager
 * @package App\Manager
 */
class MeetingManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ContainerBagInterface
     */
    private $params;

    /**
     * @var SendEmailService
     */
    private $emailService;

    /**
     * MeetingManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     * @param ContainerBagInterface  $params
     * @param SendEmailService       $emailService
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, ContainerBagInterface $params, SendEmailService $emailService)
    {
        parent::__construct($em, Meeting::class, $validator);
        $this->em = $em;
        $this->params = $params;
        $this->emailService = $emailService;
        putenv('BBB_SECRET='.$this->params->get('app.bbb_secret'));
        putenv('BBB_SERVER_BASE_URL='.$this->params->get('app.bbb_server_base_url'));
    }

    /**
     * @return object[]
     */
    public function getToUpdateMeeting()
    {
        return $this->repository->findBy(
            ['status' => 0],
            ['createdAt' => 'DESC']
        );
    }

    /**
     * @param User $user
     * @return object|null
     */
    public function getUserLastMeeting(User $user)
    {
        return $this->repository->findOneBy(
            ['user' => $user],
            ['id' => 'DESC']
        );
    }

    /**
     * @param $identifiant
     * @return object|null
     */
    public function meetingByIdentifiant($identifiant)
    {
        return $this->repository->findOneBy(
            ['identifiant' => $identifiant],
            ['id' => 'DESC']
        );
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getUserMeetingList(User $user)
    {
        return $this->repository->getUserMeetingList($user);
    }

    /**
     * @param Request           $request
     * @param ParameterManager  $paramManager
     * @param                   $urlBbb
     * @param                   $secretBbb
     * @param User              $user
     *
     * @return string
     *
     * @throws \Exception
     */
    public function createMeeting(Request $request, ParameterManager $paramManager, $urlBbb, $secretBbb, User $user)
    {
        $meetingUser = $this->getUserLastMeeting($user);
        $paramUser = $paramManager->getParamUser($user);
        $maxNumberParticipant = (int)$user->getSubscriptionUser()->getNumberParticipant();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        if (empty($paramUser) ) {
            $paramManager->setDefaultParam($user);
            $paramUser = $paramManager->getParamUser($user);
        }
        $duration = ($meetingUser->getDurationH()*60) + $meetingUser->getDurationM();
        $pwdModerator = $this->passwordModerator($meetingUser->getPassword());

        $bbb = new BigBlueButton();
        $createMeetingParams = new CreateMeetingParameters($meetingUser->getId(), $meetingUser->getSubject());
        $createMeetingParams->setAttendeePassword($meetingUser->getPassword());
        $createMeetingParams->setModeratorPassword($pwdModerator);
        $createMeetingParams->setDuration($duration);
        $createMeetingParams->setLogoutUrl($baseurl);
        $createMeetingParams->setMaxParticipants($maxNumberParticipant);

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
            $this->save($meetingUser);
            $urlMask = $baseurl . '/reunion/' . $meetingUser->getIdentifiant();
//            $this->sendMailToParticipants($meetingUser->getParticipants(), $urlMask);
            $this->sendMailToParticipants($meetingUser, $urlMask);

            return $url;
        }
    }

    /**
     * @param Meeting $meeting
     * @return string[]
     */
    public function detailMeeting(Meeting $meeting)
    {
        $participant = '';
        foreach($meeting->getParticipants() as $row) {
            $participant .= $row->getEmail(). ' ';
        }

        $content = '';
        $content .= '<div class="row my-2 justify-content-center"><div class="col-4 encart_home_head text-left">Sujet:</div><div class="col-6 encart_home_body text-left">' . $meeting->getSubject() . '</div></div>';
        $content .= '<div class="row my-2 justify-content-center"><div class="col-4 encart_home_head text-left">Description:</div><div class="col-6 encart_home_body text-left">' . $meeting->getDescription() . '</div></div>';
        $content .= ($meeting->getDurationH() || $meeting->getDurationM()) ? '<div class="row my-2 justify-content-center"><div class="col-4 encart_home_head text-left">Durée:</div><div class="col-6 encart_home_body text-left">' . $meeting->getDurationH() . ' h - '.$meeting->getDurationM().' min</div></div>' : '';
        $content .= $participant ? '<div class="row my-2 justify-content-center"><div class="col-4 encart_home_head text-left">Participant(s):</div><div class="col-6 encart_home_body text-left">' . $participant . '</div></div>' : '';
        $content .= '';

        return [
            'content' => $content,
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
        ];
    }

    /**
     * @param $entity
     *
     * @return object[]
     */
    public function deleteMeeting($entity, User $user)
    {
        $this->delete($entity);

        return $this->getUserMeetingList($user);
    }

    /**
     * @param Meeting $meeting
     * @param $url
     * @param $secret
     * @return bool
     */
    public function endMeeting(Meeting $meeting, $url, $secret)
    {
        $bbb = new BigBlueButton();
        $endMeetingParams = new EndMeetingParameters($meeting->getId(), $meeting->getPasswordModerator());
        $response = $bbb->endMeeting($endMeetingParams);

        if ($response->getReturnCode() == 'FAILED') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * State = 0 => en attente, State = 1 => en cours State = 2 => terminé
     *
     * @param Meeting $meeting
     * @param         $url
     * @param         $secret
     *
     * @return string
     */
    public function getInfoMeeting(Meeting $meeting, $url, $secret)
    {
        $bbb = new BigBlueButton();

        $getMeetingInfoParams = new GetMeetingInfoParameters($meeting->getId(), $meeting->getPasswordModerator());
        $response = $bbb->getMeetingInfo($getMeetingInfoParams);
        switch ($response->getReturnCode()) {
            case 'FAILED':
                $state = 2;
                break;
            case 'SUCCESS':
                switch ($response->getRawXml()->running) {
                    case 'false':
                        $state = 0;
                        break;
                    case 'true':
                        $state = 1;
                        break;
                }
        }

        return $state;
    }

    /**
     * @return string
     */
    public function getStatMeeting()
    {
        $barData = '{ y:"Statistique des réunions"';
        $meetings =  $this->repository->statMeeting();
        $nbr = count($meetings);
        for ($i=0; $i < $nbr; $i++) {
            if (!$meetings[$i]['state']) {
                continue;
            }
            $barData .= ', '.$meetings[$i]['state'].':'.$meetings[$i]['nbr'];
        }
        $barData .= '}';

        return $barData;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getStatMeetingPerUser($id)
    {
        $meeting = $this->repository->statMeetingPerUser($id);

        return $meeting;
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

        $bbb = new BigBlueButton();

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
     * @param $emailParticipants
     * @param $urlMeeting
     *
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendMailToParticipants($meetingUser, $urlMeeting)
    {
        $template = 'emails/meeting/sendMail.html.twig';
        $context = [
            'message1' => EmailMeetingConstant::_MESSAGE_TO_SEND_1_,
            'join_meeting' => EmailMeetingConstant::_JOIN_MEETING_,
            'pwd_meeting' => EmailMeetingConstant::_PWD_MEETING_,
            'date_meeting' => EmailMeetingConstant::_DATE_MEETING_,
            'signature' => EmailMeetingConstant::_SIGNATURE_,
            'urlMeeting' => $urlMeeting,
            'subject_meeting' => $meetingUser->getSubject() ??  '',
            'description_meeting' => $meetingUser->getDescription() ?? '',
            'pwd' => $meetingUser->getPassword(),
            'date' => $meetingUser->getDate(),
        ];

        foreach ($meetingUser->getParticipants() as $row) {
            $this->emailService->sendEmail($_ENV['CONTACT_MAIL'], $row->getEmail(), 'Hiboo: Invitation à une réunion.', $template, $context) ;
        }
    }
}
