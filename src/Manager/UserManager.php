<?php

namespace App\Manager;

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
}
