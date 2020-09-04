<?php


namespace App\Form\Handler;


use App\Entity\User;
use App\Manager\ParameterManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ParamHandler extends Handler
{
    /**
     * @var User
     */
    private $user;

    /**
     * ParamHandler constructor.
     *
     * @param FormInterface     $form
     * @param Request           $request
     * @param ParameterManager  $em
     * @param User              $user
     */
    public function __construct(FormInterface $form, Request $request, ParameterManager $em, User $user)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->user = $user;
    }

    /**
     * @return mixed|void
     */
    function onSuccess()
    {
        $param = $this->form->getData();
        $param->setUser($this->user);
        $this->em->saveOrUpdate($param);

        return true;
    }
}