<?php

namespace App\Manager;

use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SubscriptionManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * SubscriptionManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Subscription::class, $validator);
        $this->em = $em;
    }

    /**
     * @return object|null
     */
    public function getFreeSubscription()
    {
        return $this->repository->findOneBy(
            ['mode' => 'free']
        );
    }

    /**
     * @return object[]
     */
    public function getPayingSubscription()
    {
        return $this->repository->findBy(
            ['mode' => 'paying'],
            ['price' => 'ASC']
        );
    }

}
