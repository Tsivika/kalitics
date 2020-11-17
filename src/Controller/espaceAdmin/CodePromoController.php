<?php

namespace App\Controller\espaceAdmin;

use App\Entity\CodePromo;
use App\Form\CouponAdminType;
use App\Manager\CodePromoManager;
use Knp\Component\Pager\PaginatorInterface;
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
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * CodePromoController constructor.
     *
     * @param CodePromoManager $em
     * @param PaginatorInterface $paginator
     */
    public function __construct(CodePromoManager $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="app_espace_admin_codePromo_list")
     *
     * @return Response
     */
    public function codePromoList(Request $request)
    {
        $result = $this->em->findAll();
        $coupon = new CodePromo();
        $form = $this->createForm(CouponAdminType::class, $coupon);
        $codePromo = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );

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
        $result = $this->em->findAll();
        $codePromos = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );

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
    public function codePromoDelete(Request $request, CodePromo $coupon)
    {
        $result = $this->em->deleteCoupon($coupon);
        $coupon = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );

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
        $result = $this->em->switchStatusCoupon($coupon, $status);
        $statusMsg = ($status === "true") ? 'Activation' : 'Désactivation';
        $coupons = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );

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
