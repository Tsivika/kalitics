<?php

namespace App\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class Handler
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return bool
     */
    public function process(){
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()){
            $this->onSuccess();
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    abstract function onSuccess();
}