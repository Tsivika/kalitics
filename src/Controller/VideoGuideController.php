<?php

namespace App\Controller;

use App\Entity\VideoGuide;
use App\Form\Handler\VideoGuideHandler;
use App\Form\VideoGuideType;
use App\Manager\VideoGuideManager;
use App\Services\ImageUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/espace_admin/video_guide", name="app_espace_admin_video_guide")
 *
 * Class VideoGuideController
 *
 * @package App\Controller
 */
class VideoGuideController extends AbstractController
{
    /**
     * @var VideoGuideManager
     */
    private $em;

    /**
     * @var
     */
    private $paginator;

    /**
     * VideoGuideController constructor.
     * @param VideoGuideManager $em
     */
    public function __construct(VideoGuideManager $em, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/add", name="_add")
     * @Route("/edit/{id}", name="_edit")
     *
     * @param Request         $request
     * @param VideoGuide|null $videoGuide
     *
     * @return Response
     */
    public function addVideoGuide(Request $request, ImageUploader $imageUploader, VideoGuide $videoGuide = null)
    {
        $videoGuideEntity = $videoGuide ?? new VideoGuide();
        $form = $this->createForm(VideoGuideType::class, $videoGuideEntity);
        $handler = new VideoGuideHandler($form, $request, $this->em, $imageUploader, $videoGuideEntity);
        $title = $videoGuide ? 'Modifier vidéo' : 'Ajouter une vidéo';

        if ($handler->process()) {
            return $this->redirectToRoute('app_espace_admin_video_guide_list');
        }

        return $this->render('espace_admin/video_guide/add.html.twig', [
            'form' => $form->createView(),
            'title' => $title,
            'titleBouton' => $videoGuide ? 'Modifier cette vidéo' : 'Ajouter cette vidéo',
        ]);
    }

    /**
     * @Route("/list", name="_list")
     *
     * @return Response
     */
    public function listVideoGuide(Request $request)
    {
        $result = $this->em->findAll();
        $videoGuides = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $videoGuides->setSortableTemplate('shared/sortable_link_colored.html.twig');

        return $this->render('espace_admin/video_guide/list.html.twig', [
            'videoGuides' => $videoGuides,
            'title' => 'Gestions des vidéos',
        ]);
    }

    /**
     * @Route("/delete/{id}",
     *     name="_delete",
     *     options={"expose"=true},
     *     methods={"get"})
     *
     * @param VideoGuide $videoGuide
     *
     * @return JsonResponse
     */
    public function deleteVideoGuide(Request $request, VideoGuide $videoGuide)
    {
        $this->em->delete($videoGuide);
        $result = $this->em->findAll();
        $videoGuides = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $videoGuides->setSortableTemplate('shared/sortable_link_colored.html.twig');

        return new JsonResponse( [
            'listHtml' => $this->renderView('espace_admin/video_guide/list_ajax.html.twig', [
                'videoGuides' => $videoGuides,
            ]),
            'body' => "<p>Vidéo supprimée.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
