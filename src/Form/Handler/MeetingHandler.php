<?php


namespace App\Form\Handler;

use App\Entity\User;
use DateTime;
use App\Manager\MeetingManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class MeetingHandler extends Handler
{
    /**
     * MeetingHandler constructor.
     *
     * @param FormInterface  $form
     * @param Request        $request
     * @param User           $user
     * @param MeetingManager $em
     */
    public function __construct(FormInterface $form, Request $request, User $user, MeetingManager $em)
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
        $uuid = Uuid::uuid4();
        $idRandom = explode('-',$uuid->toString());
        $identifiant = array_reverse($idRandom);

        $meeting = $this->form->getData();
        $meeting->setIdentifiant($identifiant[0]);
        $meeting->setUser($this->user);
        $meeting->setLink($identifiant[0]);

        $this->em->save($meeting);
    }
}