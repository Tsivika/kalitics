<?php

namespace App\Controller\espaceClient;

use App\Manager\CategoryGuideManager;
use App\Manager\GuideManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espace_client/guide")
 *
 * Class GuideController
 *
 * @package App\Controller\espaceClient
 */
class GuideController extends AbstractController
{
    /**
     * @var GuideManager
     */
    private $em;

    /**
     * @var CategoryGuideManager
     */
    private $categEm;

    /**
     * GuideController constructor.
     *
     * @param GuideManager          $em
     * @param CategoryGuideManager  $categEm
     */
    public function __construct(GuideManager $em, CategoryGuideManager $categEm)
    {
        $this->em = $em;
        $this->categEm = $categEm;
    }

    /**
     * @Route("/", name="app_espace_client_guide_list")
     *
     * @return Response
     */
    public function guideList()
    {
        $categories = $this->categEm->findAll();

        return $this->render('espace_client/guide/list.html.twig', [
            'title' => 'Liste des guides d\'utilisation Iboo',
            'categories' => $categories,
        ]);
    }
}
