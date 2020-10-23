<?php

namespace App\Controller\espaceAdmin;

use App\Entity\Testimonial;
use App\Form\Handler\TestimonialHandler;
use App\Form\TestimonialType;
use App\Manager\TestimonialManager;
use App\Services\ImageUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponseAlias;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espace_admin/testimonial", name="app_espace_admin_testimonial")
 *
 * Class TestimonialController
 *
 * @package App\Controller\espaceAdmin
 */
class TestimonialController extends AbstractController
{
    /**
     * @var TestimonialManager
     */
    private $em;

    /**
     * @var
     */
    private $paginator;

    /**
     * TestimonialController constructor.
     *
     * @param TestimonialManager $em
     */
    public function __construct(TestimonialManager $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/add", name="_add")
     * @Route("/edit/{id}", name="_edit")
     *
     * @param Request           $request
     * @param ImageUploader     $imageUploader
     * @param Testimonial|null  $testimonial
     *
     * @return RedirectResponseAlias|Response
     */
    public function addTestimonial(Request $request, ImageUploader $imageUploader, Testimonial $testimonial = null)
    {
        $testimonialEntity = $testimonial ?? new Testimonial();
        $form = $this->createForm(TestimonialType::class, $testimonialEntity);
        $handler = new TestimonialHandler($form, $request, $this->em, $imageUploader, $testimonialEntity);
        $title = $testimonial ? 'Modifier avis' : 'Ajouter un avis';

        if ($handler->process()) {
            return $this->redirectToRoute('app_espace_admin_testimonial_list');
        }

        return $this->render('espace_admin/testimonial/add.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
            'titleBouton' => $testimonial ? 'Modifier cet avis' : 'Ajouter cet avis',
        ]);
    }

    /**
     * @Route("/list", name="_list")
     *
     * @return Response
     */
    public function listTestimonial(Request $request)
    {
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $result = $this->em->findAll();
        $testimonials = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $testimonials->setSortableTemplate('shared/sortable_link.html.twig');

        return $this->render('espace_admin/testimonial/list.html.twig', [
            'testimonials' => $testimonials,
            'title' => 'Gestions des avis',
            'baseUrl' => $baseurl,
        ]);
    }

    /**
     * @Route("/delete/{id}",
     *     name="_delete",
     *     options={"expose"=true},
     *     methods={"get"})
     *
     * @param Testimonial $testimonial
     * @return JsonResponse
     */
    public function deleteTestimonial(Request $request, Testimonial $testimonial)
    {
        $this->em->delete($testimonial);
        $result = $this->em->findAll();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $testimonials = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $testimonials->setSortableTemplate('shared/sortable_link.html.twig');

        return new JsonResponse( [
            'listHtml' => $this->renderView('espace_admin/testimonial/list_ajax.html.twig', [
                'testimonials' => $testimonials,
                'baseUrl' => $baseurl,
            ]),
            'body' => "<p>L'avis est bien supprimé.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
