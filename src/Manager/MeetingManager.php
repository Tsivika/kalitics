<?php


namespace App\Manager;


use App\Entity\Meeting;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MeetingManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * MeetingManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Meeting::class, $validator);
        $this->em = $em;
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
     * @param User $user
     * @return mixed
     */
    public function getUserMeetingList(User $user)
    {
        return $this->repository->getUserMeetingList($user);
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
}
