<?php


namespace App\Manager;


use App\Entity\Parameter;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParameterManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ParameterManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
    {
        parent::__construct($em, Parameter::class, $validator);
        $this->em = $em;
    }

    public function getParamUser(User $user)
    {
        $param = $this->repository->getParamUser($user) ;

        return (count($param)>0) ? $param[0] : [];
    }

    public function setDefaultParam(User $user)
    {
        $param = new Parameter();
        $param->setUser($user);
        $this->saveOrUpdate($param);
    }
}
