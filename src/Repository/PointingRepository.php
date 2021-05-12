<?php

namespace App\Repository;

use App\Entity\Pointing;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pointing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pointing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pointing[]    findAll()
 * @method Pointing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pointing::class);
    }

    public function maxHourPerWeekUser(User $user, $dates)
    {
        $dateBegin = $dates['startDate'];
        $dateEnd = $dates['endDate'];

        $query = $this->createQueryBuilder('p');
        $query = $query
            ->select('sum(p.duration) as totalHour')
            ->andWhere('p.date >= :begin')
            ->andWhere('p.date <= :end')
            ->andWhere('p.user = :user')
            ->setParameter('begin', $dateBegin)
            ->setParameter('end', $dateEnd)
            ->setParameter('user', $user)
            ->groupBy('p.user')
        ;

        $query = $query
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $query;
    }
}
