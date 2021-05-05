<?php


namespace App\Manager;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager extends BaseManager
{
    /**
     * UserManager constructor.
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, User::class, $validator);
        $this->em = $em;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function saveUser(User $user): bool
    {
        $this->saveOrUpdate($user);

        return true;
    }

    /**
     * @param int $idUser
     * @return mixed
     */
    public function getTotalHour(int $idUser)
    {
        $user = $this->find($idUser);

        return $this->repository->totalHourUser($user);
    }
}
