<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Form\Handler\PartnerHandler;
use App\Form\PartnerType;
use App\Manager\PartnerManager;
use App\Services\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espace_admin/partner", name="app_espace_admin_partner")
 *
 * Class PartnerController
 *
 * @package App\Controller\espaceAdmin
 */
class PartnerController extends AbstractController
{
    /**
     * @var PartnerManager
     */
    private $em;

    /**
     * PartnerController constructor.
     *
     * @param PartnerManager $em
     */
    public function __construct(PartnerManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/add", name="_add")
     * @Route("/edit/{id}", name="_edit")
     *
     * @param Request $request
     * @param Partner|null $partner
     * @return RedirectResponse|Response
     */
    public function addPartner(Request $request, ImageUploader $imageUploader, Partner $partner = null)
    {
        $partnerEntity = $partner ?? new Partner();
        $form = $this->createForm(PartnerType::class, $partnerEntity);
        $handler = new PartnerHandler($form, $request, $this->em, $imageUploader, $partnerEntity);
        $title = $partner ? 'Modifier partenaire' : 'Ajouter un partenaire';

        if ($handler->process()) {
            return $this->redirectToRoute('app_espace_admin_partner_list');
        }

        return $this->render('espace_admin/partner/add.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
            'titleBouton' => $partner ? 'Modifier ce partenaire' : 'Ajouter ce partenaire',
        ]);
    }

    /**
     * @Route("/list", name="_list")
     *
     * @return Response
     */
    public function listPartner()
    {
        $partners = $this->em->findAll();

        return $this->render('espace_admin/partner/list.html.twig', [
            'partners' => $partners,
            'title' => 'Gestions des partenaires',
        ]);
    }


    /**
     * @Route("/delete/{id}",
     *     name="_delete",
     *     options={"expose"=true},
     *     methods={"get"})
     *
     * @param Partner $partner
     * @return JsonResponse
     */
    public function deletePartner(Partner $partner)
    {
        $this->em->delete($partner);
        $partners = $this->em->findAll();

        return new JsonResponse( [
            'listHtml' => $this->renderView('espace_admin/partner/list_ajax.html.twig', [
                'partners' => $partners,
            ]),
            'body' => "<p>Partenaire supprimé.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
