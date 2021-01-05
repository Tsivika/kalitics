<?php


namespace App\Manager;


use App\Constants\EmailMeetingConstant;
use App\Entity\Participant;
use App\Services\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParticipantManager extends BaseManager
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
     * @var RequestStack
     */
    private $request;
    /**
     * @var UrlHelper
     */
    private $urlHelper;
    /**
     * @var RequestContext
     */
    private $context;

    /**
     * ParticipantManager constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @param SendEmailService $emailService
     * @param RequestContext $context
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, SendEmailService $emailService, RequestContext $context)
    {
        parent::__construct($em, Participant::class, $validator);
        $this->em = $em;
        $this->emailService = $emailService;
        $this->context = $context;
    }

    /**
     * @return false
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function notifyParticipants()
    {
        $template = 'emails/meeting/email_notification.html.twig';
        $baseurl = $this->context->getScheme() . '://' . $this->context->getHost();
        $participants = $this->repository->getParticipantsMeeting();
        $subject = EmailMeetingConstant::_MESSAGE_TO_SEND_1_;
        foreach ($participants as $row) {
            if ($row['email']) {
                $context = [
                    'message1' => $subject,
                    'join_meeting' => EmailMeetingConstant::_JOIN_MEETING_,
                    'pwd_meeting' => EmailMeetingConstant::_PWD_MEETING_,
                    'date_meeting' => EmailMeetingConstant::_DATE_MEETING_,
                    'signature' => EmailMeetingConstant::_SIGNATURE_,
                    'urlMeeting' => $baseurl . '/reunion/' . $row['identifiant'] . '/' . $row['id'],
                    'subject_meeting' => $row['subject'],
                    'description_meeting' => $row['description'],
                    'pwd' => $row['password'],
                    'date' => $row['date'],
                ];
                $this->emailService->sendEmail(
                    EmailMeetingConstant::_SENDER_NAME_. '<' .$_ENV['CONTACT_MAIL'] .'>',
                    $row['email'],
                    $subject,
                    $template,
                    $context
                );
            } else {
                return false;
            }
        }
    }

    /**
     * @param $id
     *
     * @return Participant|null
     */
    public function getById($id)
    {
        $participant = $this->find($id);

        return $participant;
    }
}
