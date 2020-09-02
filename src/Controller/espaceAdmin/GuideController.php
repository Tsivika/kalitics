<?php


namespace App\Controller\espaceAdmin;


use App\Entity\Guide;
use App\Form\GuideType;
use App\Manager\GuideManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espace_admin/guide")
 *
 * Class GuideController
 *
 * @package App\Controller\espaceAdmin
 */
class GuideController extends AbstractController
{
    /**
     * @var GuideManager
     */
    private $em;

    /**
     * GuideController constructor.
     *
     * @param GuideManager $em
     */
    public function __construct(GuideManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/",
     *     name="app_espace_admin_guide_list"
     * )
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function guideList(Request $request)
    {
        $guides = $this->em->findAll();
        $guide = new Guide();
        $form = $this->createForm(GuideType::class, $guide);

        return $this->render('espace_admin/guide/list.html.twig', [
            'guides' => $guides,
            'title' => 'Liste des guide',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add",
     *     name="app_espace_admin_guide_add",
     *     options={"expose"=true},
     *     methods={"post"})
     *
     * @return JsonResponse|Response
     */
    public function guideAdd(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->em->saveGuide($data);
        $guides = $this->em->findAll();

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_admin/guide/list_ajax.html.twig', [
                'guides' => $guides,
            ]),
            'body' => 'Guide ajoutée',
            'footer' => '',
            'success' => true,
        ]) ;
    }

    /**
     * @Route("/delete/{id}",
     *  name="app_espace_admin_guide_delete",
     *  options={"expose"=true},
     *  methods={"get"})
     *
     * @param Guide $guide
     *
     * @return JsonResponse
     */
    public function categoryDelete(Guide $guide)
    {
        $guides = $this->em->deleteGuide($guide);

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_admin/guide/list_ajax.html.twig', [
                'guides' => $guides,
            ]),
            'body' => 'Guide supprimée',
            'footer' => '',
            'success' => true,
        ]) ;
    }
}
