<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param User $user
     * @return int|mixed|string|null
     * @throws NonUniqueResultException
     */
    public function totalHourUser(User $user)
    {
        $query = $this->createQueryBuilder('u');
        $query->select('SUM(p.duration) as totalHour')
            ->join('u.pointings', 'p')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user);

        return $query
            ->getQuery()
            ->getOneOrNullResult();
    }
}
