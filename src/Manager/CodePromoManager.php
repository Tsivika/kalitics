<?php


namespace App\Manager;


use App\Entity\CodePromo;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
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

    /**
     * @param $data
     */
    public function saveCoupon($data)
    {
        $coupon = new CodePromo();
        $coupon->setName($data['name'])
            ->setReduction($data['reduction'])
            ->setCode($data['code'])
            ->setStatus($data['status']);
        $this->saveOrUpdate($coupon);
    }

    /**
     * @param $entity
     *
     * @return object[]
     */
    public function deleteCoupon($entity)
    {
        $this->delete($entity);

        return $this->repository->findAll();
    }

    /**
     * @param $entity
     * @param $status
     *
     * @return object[]
     */
    public function switchStatusCoupon($entity, $status)
    {
        $statusCode = ($status == "true") ? 1 : 0;
        $entity->setStatus($statusCode);
        $this->saveOrUpdate($entity);

        return $this->repository->findAll();
    }
}
