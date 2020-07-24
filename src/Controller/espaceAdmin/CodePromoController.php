<?php

namespace App\Controller\espaceAdmin;

use App\Manager\CodePromoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/espace_admin/coupon")
 *
 * Class CodePromoController
 *
 * @package App\Controller\espaceAdmin
 */
class CodePromoController extends AbstractController
{
    /**
     * @var CodePromoManager
     */
    private $em;

    /**
     * CodePromoController constructor.
     *
     * @param CodePromoManager $em
     */
    public function __construct(CodePromoManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="app_espace_admin_codePromo_list")
     *
     * @return Response
     */
    public function codePromoList(Request $request)
    {
        $codePromo = $this->em->findAll();

        return $this->render('espace_admin/code_promo/list.html.twig', [
            'title' => 'Liste des coupons',
        ]);
    }
}