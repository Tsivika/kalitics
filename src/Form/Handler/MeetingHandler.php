<?php


namespace App\Form\Handler;

use App\Entity\User;
use DateTime;
use App\Manager\MeetingManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;


class MeetingHandler extends Handler
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * MeetingHandler constructor.
     *
     * @param FormInterface  $form
     * @param Request        $request
     * @param User           $user
     * @param MeetingManager $em
     */
    public function __construct(FormInterface $form, Request $request, User $user, MeetingManager $em, RouterInterface $router)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->user = $user;
        $this->router = $router;
    }

    /**
     * @return mixed|void
     */
    function onSuccess()
    {
        $path = $this->request->getScheme() . '://' . $this->request->getHttpHost() . $this->request->getBasePath();
        $uuid = Uuid::uuid4();
        $idRandom = explode('-',$uuid->toString());
        $identifiant = array_reverse($idRandom);

        $meeting = $this->form->getData();
        $meeting->setIdentifiant($identifiant[0]);
        $meeting->setUser($this->user);
        $meeting->setLink($path.'/p/'.$identifiant[0]);

        $this->em->save($meeting);

        return true;
    }

    /**
     * @param $meeting
     *
     * @return bool|string
     */
    public function restriction($meeting)
    {
        $participantSubscription = $this->user->getSubscriptionUser()->getNumberParticipant();
        $durationSubscription = $this->user->getSubscriptionUser()->getDurationMeeting();
        $durationInput = $meeting->getDurationM();
        $participantInput = count($meeting->getParticipants());
        $subscription = $this->user->getSubscriptionUser()->getMode();

        if ($subscription === 'free') {
            if ($durationInput > (int) $durationSubscription) {
                $error = 'La durée de votre réunion est de : ' . $durationSubscription;

                return $error;
            }
            if ($participantInput > (int) $participantSubscription) {
                $error = 'Vous avez atteint le nombre maximum de participants, qui est de: ' .$participantSubscription;

                return $error;
            }
        }

        return true;
    }
}
