<?php

namespace App\Form\Handler;

use App\Manager\AccountManager;
use App\Services\StripePayement;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class AccountHandler extends Handler
{
    private $stripe;
    /**
     * AccountHandler constructor.
     *
     * @param FormInterface  $form
     * @param Request        $request
     * @param AccountManager    $em
     */
    public function __construct(FormInterface $form, Request $request, AccountManager $em, StripePayement $stripe)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->stripe = $stripe;
    }

    /**
     * @return mixed|void
     */
    function onSuccess()
    {
        $dataUser = $this->form->getData();
        $dataRequest = $this->request->request->all();

        $userDetail = [
            'userId' => $dataUser->getId(),
            'userEmail' => $dataUser->getEmail(),
            'userEntreprise' => $dataUser->getEntreprise(),
            'userAddress' => $dataUser->getAddress(),
        ];

        $userAccount = [
            'reduction' => $dataRequest['reduction'],
            'codePromo' => $dataRequest['user_account']['codePromo'],
            'stripeToken' => $dataRequest['stripeToken'],
            'total_paid' => $dataRequest['total-paid'],
            'subscription' => $dataRequest['user_account']['subscription'],
        ];

        $this->em->subscribe($userDetail, $userAccount);
    }
}
