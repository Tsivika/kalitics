<?php


namespace App\Form\Handler;


use App\Entity\VideoGuide;
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
     * @var ImageUploader
     */
    private $imageUploader;

    private $videoGuide;
    /**
     * VideoGuideHandler constructor.
     *
     * @param FormInterface     $form
     * @param Request           $request
     * @param VideoGuideManager $em
     */
    public function __construct(FormInterface $form, Request $request, VideoGuideManager $em, ImageUploader $imageUploader, VideoGuide $videoGuide)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->imageUploader = $imageUploader;
        $this->videoGuide = $videoGuide;
    }

    /**
     * @return bool|mixed
     */
    function onSuccess()
    {
        $titre = $this->form->get('titre')->getData();
        $imageFile = $this->form->get('pdc')->getData();
        $url = $this->form->get('url')->getData();
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $url, $match);

        if ($imageFile) {
            $imageFileName = $this->imageUploader->upload($imageFile);
            $this->videoGuide->setPdc($imageFileName);
        }

        $this->videoGuide->setTitre($titre);
        $this->videoGuide->setUrl($match[0][0]);

        $this->em->saveOrUpdate($this->videoGuide);

        return true;
    }
}