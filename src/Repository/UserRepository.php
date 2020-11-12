<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
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
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * selects users registered over the last 03 months
     *
     * @return int|mixed|string
     */
    public function lastRegistered()
    {
        $now = new \DateTime("today");
        $date = new \DateTime("today");
        $date->sub(new \DateInterval("P90D" ));

        $query = $this->createQueryBuilder('u');
        $query = $query
            ->select('count(u.id) as nbr', 'u.createdAt')
            ->andWhere('u.createdAt >= :date1')
            ->andWhere('u.createdAt <= :date2')
            ->setParameter('date1', $date)
            ->setParameter('date2', $now)
            ->groupBy('u.createdAt')
            ->orderBy('u.createdAt', 'ASC' )
        ;

        $query = $query
            ->getQuery()
            ->getResult()
        ;

        return $query;
    }

    /**
     * @return int|mixed|string
     */
    public function statUserSubscription()
    {
        $now = new \DateTime("today");
        $date = new \DateTime("today");
        $date->sub(new \DateInterval("P90D" ));

        $query = $this->createQueryBuilder('u');
        $query = $query
            ->select('count(u.id) as nbr', 's.title')
            ->leftJoin('u.subscriptionUser', 's')
            ->andWhere('s.id = u.subscriptionUser')
            ->andWhere('u.createdAt >= :date1')
            ->andWhere('u.createdAt <= :date2')
            ->setParameter('date1', $date)
            ->setParameter('date2', $now)
            ->groupBy( 's.title')
        ;

        $query = $query
            ->getQuery()
            ->getResult()
        ;

        return $query;
    }

    public function getUserNotDeleted()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.deleted = 0')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function updateUser()
    {
        $updateUser = $this->createQueryBuilder('u')
            ->update(User::class, 'u')
            ->set('u.deleted', 0)
            ->set('u.active', 0)
            ->getQuery();
        $updateUser->execute();

        return $updateUser;
    }
}
