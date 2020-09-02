<?php


namespace App\Form\Handler\admin;


use App\Form\Handler\Handler;
use App\Manager\CodePromoManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CodePromoHandler extends Handler
{
    /**
     * CodePromoHandler constructor.
     *
     * @param FormInterface     $form
     * @param Request           $request
     * @param CodePromoManager  $em
     */
    public function __construct(FormInterface $form, Request $request, CodePromoManager $em)
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
        $codePromo = $this->form->getData();
        $this->em->save($codePromo);
    }
}