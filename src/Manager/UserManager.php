<?php

namespace App\Manager;

use DateTime;
use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UserManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, User::class, $validator);
        $this->em = $em;
    }

    /**
     * @param User          $user
     * @param Subscription  $subscription
     *
     * @return Subscription|null
     */
    public function userSubscriptionChoice(User $user, Subscription $subscription)
    {
        $user->setSubscriptionUser($subscription);
        $this->saveOrUpdate($user);

        return $user->getSubscriptionUser();
    }

    /**
     * @param User                  $user
     * @param SubscriptionManager   $subscriptionManager
     *
     * @return Subscription|null
     */
    public function userSubscriptionDeactive(User $user, SubscriptionManager $subscriptionManager)
    {
        $freeSubscription = $subscriptionManager->getFreeSubscription();
        $user->setSubscriptionUser($freeSubscription);
        $this->saveOrUpdate($user);

        return $user->getSubscriptionUser();
    }

    /**
     * @return false|string
     * @throws \Exception
     */
    public function statUserSubscriber()
    {
        $lineData = '';
        $subscribers =  $this->repository->lastRegistered();
        foreach ($subscribers as $row) {
            $d = ($row['createdAt'] instanceof \DateTime)
                ? \DateTimeImmutable::createFromMutable($row['createdAt'])
                : new \DateTimeImmutable($row['createdAt']);
            $lineData .= "{ y:'".$d->format('Y-m-d')."', item1:".$row["nbr"]."},";
        }
        $lineData = substr($lineData, 0, -1);

        return $lineData;
    }

    /**
     * @return false|string
     *
     * @throws \Exception
     */
    public function getStatUserSubscription()
    {
        $donutData = '';
        $userSubscription =  $this->repository->statUserSubscription();
        foreach ($userSubscription as $row) {
            $donutData .= "{ label:'".$row["title"]."', value:".$row["nbr"]."},";
        }
        $donutData = substr($donutData, 0, -1);

        return $donutData;
    }

    public function changeRole(User $user, $role)
    {
        $roles[] = $role;
        $user->setRoles($roles);
        $this->saveOrUpdate($user);

        return $user;
    }
}
