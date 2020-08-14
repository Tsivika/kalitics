<?php

namespace App\Controller\espaceAdmin;

use App\Entity\Category;
use App\Form\CategoryGuideType;
use App\Manager\CategoryGuideManager;
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
     * CategoryGuideController constructor.
     *
     * @param CategoryGuideManager $em
     */
    public function __construct(CategoryGuideManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_espace_admin_category_guide")
     *
     */
    public function categoryList(Request $request)
    {
        $categories = $this->em->findAll();
        $category = new Category();
        $form = $this->createForm(CategoryGuideType::class, $category);

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
     * @return JsonResponse|Response
     */
    public function categoryAdd(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->em->saveCategory($data);
        $categories = $this->em->findAll();

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
     * @param Category $category
     *
     * @return JsonResponse
     */
    public function categoryDelete(Category $category)
    {
        $category = $this->em->deleteCategory($category);

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
