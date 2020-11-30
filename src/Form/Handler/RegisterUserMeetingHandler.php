<?php


namespace App\Form\Handler;

use DateTime;
use App\Entity\User;
use App\Manager\RegisterManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Ramsey\Uuid\Uuid;

class RegisterUserMeetingHandler extends Handler
{
    /**
     * @var User
     */
    private $user;

    /**
     * RegisterUserMeetingHandler constructor.
     *
     * @param FormInterface   $form
     * @param Request         $request
     * @param User            $user
     * @param RegisterManager $em
     */
    public function __construct(FormInterface $form, Request $request, User $user, RegisterManager $em)
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

        $meeting->setSubject('');
        $meeting->setDescription('');
        $meeting->setDurationM(20);
        $meeting->setDate(new DateTime('now'));
        $meeting->setIdentifiant($identifiant[0]);
        $meeting->setUser($this->user);
        $meeting->setPassword('passMeetingHiboo');

        $this->em->save($meeting);
    }
}
