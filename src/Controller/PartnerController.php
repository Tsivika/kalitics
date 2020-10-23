<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Form\Handler\PartnerHandler;
use App\Form\PartnerType;
use App\Manager\PartnerManager;
use App\Services\ImageUploader;
use Knp\Component\Pager\PaginatorInterface;
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
     * @var
     */
    private $paginator;

    /**
     * PartnerController constructor.
     *
     * @param PartnerManager $em
     */
    public function __construct(PartnerManager $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
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
    public function listPartner(Request $request)
    {
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $result = $this->em->findAll();
        $partners = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $partners->setSortableTemplate('shared/sortable_link.html.twig');

        return $this->render('espace_admin/partner/list.html.twig', [
            'partners' => $partners,
            'title' => 'Gestions des partenaires',
            'baseUrl' => $baseurl,
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
    public function deletePartner(Request $request, Partner $partner)
    {
        $this->em->delete($partner);
        $result = $this->em->findAll();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $partners = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $partners->setSortableTemplate('shared/sortable_link.html.twig');

        return new JsonResponse( [
            'listHtml' => $this->renderView('espace_admin/partner/list_ajax.html.twig', [
                'partners' => $partners,
                'baseUrl' => $baseurl,
            ]),
            'body' => "<p>Partenaire supprimé.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
