<?php


namespace App\Manager;


use App\Entity\Partner;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PartnerManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * PartnerManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Partner::class, $validator);
        $this->em = $em;
    }
}