<?php

namespace App\Manager;

use App\Entity\Guide;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GuideManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $categEm;
    /**
     * GuideManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, CategoryGuideManager $categEm)
    {
        parent::__construct($em, Guide::class, $validator);
        $this->em = $em;
        $this->categEm = $categEm;
    }

    /**
     * @param $data
     */
    public function saveGuide($data)
    {
        $guide = new Guide();
        $category = $this->categEm->find($data['category']);
        $guide->setQuestion($data['question']);
        $guide->setResponse($data['response']);
        $guide->setCategory($category);
        $this->saveOrUpdate($guide);
    }

    /**
     * @param $entity
     *
     * @return object[]
     */
    public function deleteGuide($entity)
    {
        $this->delete($entity);

        return $this->repository->findAll();
    }
}
