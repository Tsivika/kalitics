<?php

namespace App\Controller\espaceAdmin;

use App\Entity\Meeting;
use App\Entity\TimestampableEntityTrait;
use App\Form\Handler\MeetingHandler;
use App\Form\MeetingType;
use App\Manager\MeetingManager;
use App\Manager\ParameterManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/espace_admin/meeting")
 *
 * Class MeetingController
 *
 * @package App\Controller\espaceAdmin
 */
class MeetingController extends AbstractController
{
    /**
     * @var MeetingManager
     */
    private $em;

    /**
     * @var ContainerBagInterface
     */
    private $params;

    /**
     * @var mixed
     */
    private $urlBbb;

    /**
     * @var mixed
     */
    private $secretBbb;

    /**
     * @var
     */
    private $paginator;

    /**
     * MeetingController constructor.
     *
     * @param MeetingManager        $em
     * @param ContainerBagInterface $params
     */
    public function __construct(MeetingManager $em, ContainerBagInterface $params, PaginatorInterface $paginator)
    {
        $this->em = $em;
        $this->params = $params;
        $this->urlBbb = $this->params->get('app.bbb_server_base_url');
        $this->secretBbb = $this->params->get('app.bbb_secret');
        $this->paginator = $paginator;
    }

    /**
     * @Route("/add", name="app_espace_admin_meeting_add")
     *
     * @param Request       $request
     * @param Meeting|null  $meeting
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function meetingAdd(Request $request, RouterInterface $router, ParameterManager $paramManager)
    {
        $meetingEntity = new Meeting();
        $title =  'Ajouter une réunion';

        $form = $this->createForm(MeetingType::class, $meetingEntity);
        $handler = new MeetingHandler($form, $request, $this->getUser(), $this->em, $router);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if ($handler->onSuccess()) {
                $handler->createMeeting($paramManager, $this->urlBbb, $this->secretBbb);

                return $this->redirectToRoute('app_espace_admin_meeting_list');
            }
        }

        return $this->render('espace_admin/meeting/add.html.twig', [
            'title' => $title,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="app_espace_admin_meeting_list")
     *
     * @return Response
     */
    public function meetingList(Request $request)
    {
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $result = $this->em->findAll();
        $meetings = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $meetings->setSortableTemplate('shared/sortable_link.html.twig');

        return $this->render('espace_admin/meeting/list.html.twig', [
            'title' => 'Gestion de réunions',
            'meetings' => $meetings,
            'baseUrl' => $baseurl,
        ]);
    }

    /**
     * @Route("/delete/{id}",
     *     name="app_espace_admin_meeting_delete",
     *     options={"expose"=true},
     *     methods={"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function meetingDelete(Request $request, Meeting $meeting)
    {
        $this->em->endMeeting($meeting, $this->urlBbb, $this->secretBbb);
        $this->em->delete($meeting);
        $result = $this->em->findAll();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $meetings = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $meetings->setSortableTemplate('shared/sortable_link.html.twig');

        return new JsonResponse( [
            'listHtml' => $this->renderView('espace_admin/meeting/liste_ajax.html.twig', [
                'meetings' => $meetings,
                'baseUrl' => $baseurl,
            ]),
            'body' => "<p>La réunion est bien supprimée.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}