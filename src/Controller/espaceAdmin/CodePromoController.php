<?php

namespace App\Controller\espaceAdmin;

use App\Entity\CodePromo;
use App\Form\CouponAdminType;
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
        $coupon = new CodePromo();
        $form = $this->createForm(CouponAdminType::class, $coupon);

        return $this->render('espace_admin/code_promo/list.html.twig', [
            'title' => 'Liste des coupons',
            'codePromos' => $codePromo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/add",
     *     name="app_espace_admin_coupon_add",
     *     options={"expose"=true},
     *     methods={"post"})
     *
     * @return JsonResponse|Response
     */
    public function codePromoAdd(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->em->saveCoupon($data);
        $codePromos = $this->em->findAll();

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_admin/code_promo/liste_ajax.html.twig', [
                'codePromos' => $codePromos,
            ]),
            'body' => 'Coupon ajouté',
            'footer' => '',
            'success' => true,
        ]) ;
    }

    /**
     * @Route("/delete/{id}",
     *  name="app_espace_admin_coupon_delete",
     *  options={"expose"=true},
     *  methods={"get"})
     *
     * @param CodePromo $coupon
     *
     * @return JsonResponse
     */
    public function codePromoDelete(CodePromo $coupon)
    {
        $coupon = $this->em->deleteCoupon($coupon);

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_admin/code_promo/liste_ajax.html.twig', [
                'codePromos' => $coupon,
            ]),
            'body' => 'Coupon supprimé',
            'footer' => '',
            'success' => true,
        ]) ;
    }

    /**
     * @Route("/switch_status/{id}",
     *  name="app_espace_admin_coupon_switch_status",
     *  options={"expose"=true},
     *  methods={"get"})
     *
     * @param CodePromo $coupon
     *
     * @return JsonResponse
     */
    public function couponPromoSwitchStatus(CodePromo $coupon, Request $request)
    {
        $status = $request->get('status');
        $coupons = $this->em->switchStatusCoupon($coupon, $status);
        $statusMsg = ($status === "true") ? 'Activation' : 'Désactivation';

        return new JsonResponse([
            'listHtml' => $this->renderView('espace_admin/code_promo/liste_ajax.html.twig', [
                'codePromos' => $coupons,
            ]),
            'body' => $statusMsg . ' coupon fait.' ,
            'footer' => '',
            'success' => true,
        ]) ;
    }
}
