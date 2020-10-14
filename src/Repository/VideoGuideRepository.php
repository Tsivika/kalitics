<?php

namespace App\Repository;

use App\Entity\VideoGuide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VideoGuide|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoGuide|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoGuide[]    findAll()
 * @method VideoGuide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoGuideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoGuide::class);
    }

    // /**
    //  * @return VideoGuide[] Returns an array of VideoGuide objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoGuide
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
