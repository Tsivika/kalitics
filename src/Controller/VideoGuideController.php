<?php

namespace App\Controller;

use App\Entity\VideoGuide;
use App\Form\Handler\VideoGuideHandler;
use App\Form\VideoGuideType;
use App\Manager\VideoGuideManager;
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
     * VideoGuideController constructor.
     * @param VideoGuideManager $em
     */
    public function __construct(VideoGuideManager $em)
    {
        $this->em = $em;
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
    public function addVideoGuide(Request $request, VideoGuide $videoGuide = null)
    {
        $videoGuideEntity = $videoGuide ?? new VideoGuide();
        $form = $this->createForm(VideoGuideType::class, $videoGuideEntity);
        $handler = new VideoGuideHandler($form, $request, $this->em);
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
    public function listVideoGuide()
    {
        $partners = $this->em->findAll();

        return $this->render('espace_admin/video_guide/list.html.twig', [
            'partners' => $partners,
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
    public function deletePartner(VideoGuide $videoGuide)
    {
        $this->em->delete($videoGuide);
        $videoGuides = $this->em->findAll();

        return new JsonResponse( [
            'listHtml' => $this->renderView('espace_admin/video_guide/list_ajax.html.twig', [
                'partners' => $videoGuides,
            ]),
            'body' => "<p>Vidéo supprimée.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
