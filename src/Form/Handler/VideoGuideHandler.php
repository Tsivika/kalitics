<?php


namespace App\Form\Handler;


use App\Manager\VideoGuideManager;
use App\Services\ImageUploader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class VideoGuideHandler
 *
 * @package App\Form\Handler
 */
class VideoGuideHandler extends Handler
{
    /**
     * VideoGuideHandler constructor.
     *
     * @param FormInterface     $form
     * @param Request           $request
     * @param VideoGuideManager $em
     */
    public function __construct(FormInterface $form, Request $request, VideoGuideManager $em)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
    }

    /**
     * @return bool|mixed
     */
    function onSuccess()
    {
        $param = $this->form->getData();
        $this->em->saveOrUpdate($param);

        return true;
    }
}