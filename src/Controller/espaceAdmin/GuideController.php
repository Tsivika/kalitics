<?php


namespace App\Controller\espaceAdmin;

use App\Entity\Guide;
use App\Form\GuideType;
use App\Manager\GuideManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * GuideController constructor.
     *
     * @param GuideManager $em
     * @param PaginatorInterface $paginator
     */
    public function __construct(GuideManager $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/",
     *     name="app_espace_admin_guide_list"
     * )
     *
     * @param Request $request
     *
     * @return ResponseAlias
     */
    public function guideList(Request $request)
    {
        $result = $this->em->findAll();
        $guide = new Guide();
        $form = $this->createForm(GuideType::class, $guide);
        $guides = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $guides->setSortableTemplate('shared/sortable_link.html.twig');

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
        $result = $this->em->findAll();
        $guides = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $guides->setSortableTemplate('shared/sortable_link.html.twig');
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
    public function categoryDelete(Request $request, Guide $guide)
    {
        $result = $this->em->deleteGuide($guide);
        $guides = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $guides->setSortableTemplate('shared/sortable_link.html.twig');
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
