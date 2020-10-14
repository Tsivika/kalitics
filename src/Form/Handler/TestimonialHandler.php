<?php

namespace App\Form\Handler;

use App\Entity\Testimonial;
use App\Manager\TestimonialManager;
use App\Services\ImageUploader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TestimonialHandler
 *
 * @package App\Form\Handler
 */
class TestimonialHandler extends Handler
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var Testimonial
     */
    private $testimonial;

    /**
     * TestimonialHandler constructor.
     *
     * @param FormInterface      $form
     * @param Request            $request
     * @param TestimonialManager $em
     * @param ImageUploader      $imageUploader
     */
    public function __construct(FormInterface $form, Request $request, TestimonialManager $em, ImageUploader $imageUploader, Testimonial $testimonial)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->imageUploader = $imageUploader;
        $this->testimonial = $testimonial;
    }

    /**
     * @return bool|mixed
     */
    function onSuccess()
    {
        $imageFile = $this->form->get('image')->getData();
        $name = $this->form->get('name')->getData();
        $score = $this->form->get('score')->getData();
        $content = $this->form->get('content')->getData();

        if ($imageFile) {
            $imageFileName = $this->imageUploader->upload($imageFile);
            $this->testimonial->setPdc($imageFileName);
        }
        $this->testimonial->setName($name)
            ->setScore($score)
            ->setContent($content);

        $this->em->saveOrUpdate($this->testimonial);

        return true;
    }
}
