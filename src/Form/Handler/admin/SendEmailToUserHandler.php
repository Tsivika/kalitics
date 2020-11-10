<?php


namespace App\Form\Handler\admin;


use App\Form\Handler\Handler;
use App\Manager\UserSubscriptionManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class SendEmailToUserHandler extends Handler
{
    public function __construct(FormInterface $form, Request $request, UserSubscriptionManager $em)
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
        $data = [
            'emailUser' => $this->request->get('email_user'),
            'subject' => $this->form->get('subject')->getData(),
            'message' => $this->form->get('message')->getData(),
        ];
        $this->em->sendEmail($data);
    }
}