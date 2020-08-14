<?php

namespace App\Form\Handler;

use App\Manager\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class AccountHandler extends Handler
{
    /**
     * AccountHandler constructor.
     *
     * @param FormInterface  $form
     * @param Request        $request
     * @param UserManager    $em
     */
    public function __construct(FormInterface $form, Request $request, UserManager $em)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
    }

    /**
     * @return mixed|void
     */
    function onSuccess()
    {
        $dataUser = $this->form->getData();
        $dataRequest = $this->request->request->all();
        dump($dataRequest);
        dump($dataUser);
        die();
    }
}