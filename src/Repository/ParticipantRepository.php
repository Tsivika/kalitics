<?php

namespace App\Repository;

use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    /**
     * @return int|mixed|string
     */
    public function getParticipantsMeeting()
    {
        $now = new \DateTime("NOW");
        $date = new \DateTime("NOW");
        $date->add(new \DateInterval("PT15M" ));

        $query = $this->createQueryBuilder('p');
        $query = $query
            ->select('m.subject, m.identifiant, m.description, m.password, m.date, p.email, p.id')
            ->join('p.meeting', 'm')
            ->andWhere('m.date <= :date2')
            ->andWhere('m.date >= :date1')
            ->setParameter('date1', $now)
            ->setParameter('date2', $date)
        ;

        $query = $query
            ->getQuery()
            ->getResult()
        ;

        return $query;
    }

    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
