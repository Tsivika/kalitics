<?php


namespace App\Form\Handler;


use App\Entity\Partner;
use App\Manager\PartnerManager;
use App\Services\ImageUploader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PartnerHandler
 *
 * @package App\Form\Handler
 */
class PartnerHandler extends Handler
{
    /**
     * @var ImageUploader
     */
    private $imageUploader;
    /**
     * @var Partner
     */
    private $partner;

    /**
     * PartnerHandler constructor.
     *
     * @param FormInterface  $form
     * @param Request        $request
     * @param PartnerManager $em
     */
    public function __construct(FormInterface $form, Request $request, PartnerManager $em, ImageUploader $imageUploader, Partner $partner)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->imageUploader = $imageUploader;
        $this->partner = $partner;
    }

    /**
     * @return mixed|void
     */
    function onSuccess()
    {
        $imageFile = $this->form->get('pdc')->getData();
        $name = $this->form->get('name')->getData();
        $webSite = $this->form->get('webSite')->getData();

        if ($imageFile) {
            $imageFileName = $this->imageUploader->upload($imageFile);
            $this->partner->setPdc($imageFileName);
        }
        $this->partner->setName($name);
        $this->partner->setWebSite($webSite);

        $this->em->saveOrUpdate($this->partner);

        return true;
    }
}
