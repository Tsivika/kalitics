<?php

namespace App\Form\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Manager\ContactManager;

class ContactHandler extends Handler
{
    /**
     * ContactHandler constructor.
     *
     * @param FormInterface     $form
     * @param Request           $request
     * @param ContactManager    $em
     */
    public function __construct(FormInterface $form, Request $request, ContactManager $em)
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
    }
}

