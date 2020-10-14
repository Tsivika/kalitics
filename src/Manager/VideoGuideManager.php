<?php


namespace App\Manager;


use App\Entity\VideoGuide;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VideoGuideManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * VideoGuideManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, VideoGuide::class, $validator);
        $this->em = $em;
    }
}