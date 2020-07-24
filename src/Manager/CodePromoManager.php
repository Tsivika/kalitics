<?php


namespace App\Manager;


use App\Entity\CodePromo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CodePromoManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CodePromoManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, CodePromo::class, $validator);
        $this->em = $em;
    }
}
