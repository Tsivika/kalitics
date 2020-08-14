<?php


namespace App\Manager;


use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CategoryGuideManager
 *
 * @package App\Manager
 */
class CategoryGuideManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CategoryGuideManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Category::class, $validator);
        $this->em = $em;
    }

    /**
     * @param $data
     */
    public function saveCategory($data)
    {
        $category = new Category();
        $category->setTitle($data['title']);
        $this->saveOrUpdate($category);
    }

    /**
     * @param $entity
     *
     * @return object[]
     */
    public function deleteCategory($entity)
    {
        $this->delete($entity);

        return $this->repository->findAll();
    }
}
