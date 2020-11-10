<?php

namespace App\Repository;

use App\Entity\Meeting;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Meeting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meeting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meeting[]    findAll()
 * @method Meeting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingRepository extends ServiceEntityRepository
{
    /**
     * MeetingRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meeting::class);
    }

    /**
     * @param User $user
     *
     * @return int|mixed|string
     */
    public function getUserMeetingList(User $user)
    {
        $query = $this->createQueryBuilder('m');
        $query = $query
            ->andWhere('m.user = :user')
            ->setParameter('user', $user);

        $query = $query
            ->getQuery()
            ->getResult()
        ;

        return $query;
    }

    /**
     * @return int|mixed|string
     */
    public function statMeeting()
    {
        $now = new \DateTime("today");
        $date = new \DateTime("today");
        $date->sub(new \DateInterval("P90D" ));

        $query = $this->createQueryBuilder('m');
        $query = $query
            ->select('count(m.id) as nbr', 'm.state')
            ->andWhere('m.createdAt >= :date1')
            ->andWhere('m.createdAt <= :date2')
            ->setParameter('date1', $date)
            ->setParameter('date2', $now)
            ->groupBy('m.state')
        ;

        $query = $query
            ->getQuery()
            ->getResult()
        ;

        return $query;
    }

    /**
     * @param $id
     * @return int|mixed|string
     */
    public function statMeetingPerUser($id)
    {
        $query = $this->createQueryBuilder('m');
        $query = $query
            ->select('count(m.id) as nbr', 'm.state')
            ->andWhere('m.user = :user')
            ->setParameter('user', $id)
            ->groupBy('m.state')
        ;

        $query = $query
            ->getQuery()
            ->getResult()
        ;

        return $query;
    }

    // /**
    //  * @return Meeting[] Returns an array of Meeting objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Meeting
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
