<?php


namespace App\Manager;


use App\Entity\Testimonial;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class TestimonialManager
 *
 * @package App\Manager
 */
class TestimonialManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TestimonialManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Testimonial::class, $validator);
        $this->em = $em;
    }
}