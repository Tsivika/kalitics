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

    public function getUserLastMeeting(User $user)
    {
        return $this->repository->findOneBy(
            ['user' => $user],
            ['id' => 'DESC']
        );
    }

    public function getUserMeetingList(User $user)
    {
        return $this->repository->getUserMeetingList($user);
    }
}