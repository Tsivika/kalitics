<?php


namespace App\Form\Handler\admin;


use App\Form\Handler\Handler;
use App\Manager\SubscriptionManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SubscriptionAdminHandler extends Handler
{
    /**
     * SubscriptionAdminHandler constructor.
     *
     * @param FormInterface         $form
     * @param Request               $request
     * @param SubscriptionManager   $em
     */
    public function __construct(FormInterface $form, Request $request, SubscriptionManager $em)
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
        $subcription = $this->form->getData();
        $this->em->save($subcription);
    }
}
