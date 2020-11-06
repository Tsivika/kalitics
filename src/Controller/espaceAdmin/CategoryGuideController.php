<?php

namespace App\Controller\espaceAdmin;

use App\Entity\Category;
use App\Form\CategoryGuideType;
use App\Manager\CategoryGuideManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/espace_admin/category_guide")
 *
 * Class CategoryGuideController
 *
 * @package App\Controller\espaceAdmin
 */
class CategoryGuideController extends AbstractController
{
    /**
     * @var CategoryGuideManager
     */
    private $em;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * CategoryGuideController constructor.
     *
     * @param CategoryGuideManager $em
     * @param PaginatorInterface $paginator
     */
    public function __construct(CategoryGuideManager $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="app_espace_admin_category_guide")
     * @param Request $request
     * @return Response
     */
    public function categoryList(Request $request)
    {
        $result = $this->em->findAll();
        $category = new Category();
        $form = $this->createForm(CategoryGuideType::class, $category);
        $categories = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('espace_admin/guide/category/list.html.twig', [
            'categories' => $categories,
            'title' => 'Liste des catégories guide',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add",
     *     name="app_espace_admin_category_add",
     *     options={"expose"=true},
     *     methods={"post"})
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function categoryAdd(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->em->saveCategory($data);
        $result = $this->em->findAll();
        $categories = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        return new JsonResponse([
            'listHtml' => $this->renderView('espace_admin/guide/category/list_ajax.html.twig', [
                'categories' => $categories,
            ]),
            'body' => 'Catégorie ajoutée',
            'footer' => '',
            'success' => true,
        ]) ;
    }

    /**
     * @Route("/delete/{id}",
     *  name="app_espace_admin_category_delete",
     *  options={"expose"=true},
     *  methods={"get"})
     *
     * @param Request $request
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function categoryDelete(Request $request, Category $category)
    {
        $result = $this->em->deleteCategory($category);
        $category = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_admin/guide/category/list_ajax.html.twig', [
                'categories' => $category,
            ]),
            'body' => 'Catégorie supprimée',
            'footer' => '',
            'success' => true,
        ]) ;
    }
}
