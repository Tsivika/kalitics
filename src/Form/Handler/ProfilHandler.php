<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Manager\UserManager;
use App\Services\ImageUploader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfilHandler extends Handler
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var User
     */
    private $user;


    /**
     * @var UserManager
     */
    protected $em;

    private $imageUploader;

    /**
     * RegisterHandler constructor.
     *
     * @param FormInterface                 $form
     * @param Request                       $request
     * @param UserPasswordEncoderInterface  $passwordEncoder
     * @param User                          $user
     * @param UserManager               $em
     */
    public function __construct(FormInterface $form, Request $request, UserPasswordEncoderInterface $passwordEncoder, User $user, UserManager $em, ImageUploader $imageUploader)
    {
        $this->form = $form;
        $this->request = $request;
        $this->passwordEncoder = $passwordEncoder;
        $this->user = $user;
        $this->em = $em;
        $this->imageUploader = $imageUploader;
    }

    /**
     * @return mixed|\Symfony\Component\HttpFoundation\Response|null
     */
    function onSuccess()
    {
        $imageFile = $this->form->get('pdp')->getData();
        if ($imageFile) {
            $imageFileName = $this->imageUploader->upload($imageFile);
            $this->user->setPdp($imageFileName);
        }

        $this->user->setPassword(
            $this->passwordEncoder->encodePassword(
                $this->user,
                $this->form->get('password')->getData()
            )
        );
        $this->em->save($this->user);
    }
}