<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $eM;

    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    protected $repository;

    /**
     * @var
     */
    protected $class;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * BaseManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param mixed                  $class
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, $class, ValidatorInterface $validator )
    {
        $this->eM = $em;
        $this->class = $class;
        $this->repository = $this->eM->getRepository($this->class);
        $this->validator = $validator;
    }

    /**
     *
     */
    public function flushAndClear()
    {
        $this->eM->flush();
    }

    /**
     * Use the Validator to validate an entity
     * Return the error messages
     *
     * @param mixed $entity
     * @param mixed $constraint additional Constraint
     * @param mixed $group      validation group
     *
     * @return array
     */
    public function makeValidation($entity, $constraint = null, $group = null)
    {
        $errors = $this->validator->validate($entity, $constraint, $group);
        $errorMessages = [];
        foreach ($errors as $err) {
            $errorMessages[$err->getPropertyPath()] = $err->getMessage();
        }

        return $errorMessages;
    }

    /**
     * @param mixed $entity Entity to Save/Persist
     *
     * @return array
     */
    public function save($entity)
    {
        $errors = $this->makeValidation($entity);
        if (count($errors) > 0) {
            return $errors;
        }
        $this->persistAndFlush($entity);

        return $entity;
    }

    /**
     * @param mixed $entity Entity to Save or Update if it has an ID
     *
     * @return array
     */
    public function saveOrUpdate($entity)
    {
        $errors = $this->makeValidation($entity);
        if (count($errors) > 0) {
            return $errors;
        }
        $entity->getId()
            ? $this->mergeAndFlush($entity)
            : $this->persistAndFlush($entity);

        return $entity;
    }

    /**
     * @param mixed $entity
     *
     * @return array|object
     */
    public function update($entity)
    {
        $errors = $this->makeValidation($entity);
        if (count($errors) > 0) {
            return $errors;
        }
        $entity = $this->eM->merge($entity);

        return $entity;
    }

    /**
     * Delete and entity
     *
     * @param mixed $entity
     *
     * @return bool
     */
    public function delete($entity)
    {
        $this->eM->remove($entity);
        $this->flushAndClear();

        return true;
    }

    /**
     * @return object[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @param int $id the Id of the entity to find
     *
     * @return object|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $array
     *
     * @return object[]
     */
    public function findBy(array $array)
    {
        return $this->repository->findBy($array);
    }

    /**
     * Persist and flush an entity
     *
     * @param mixed $entity
     *
     * @return void
     */
    protected function persistAndFlush($entity)
    {
        $this->eM->persist($entity);
        $this->flushAndClear();
    }

    /**
     * Merge and flush an entity
     *
     * @param mixed $entity
     *
     * @return void
     */
    protected function mergeAndFlush($entity)
    {
        $this->eM->merge($entity);
        $this->flushAndClear();
    }

    /**
     * @param Entity $id
     * @param null   $class
     *
     * @return mixed
     */
    public function getReference($id, $class = null)
    {
        $class = !$class ? $this->class : $class;

        return $this->eM->getReference($class, $id);
    }
}