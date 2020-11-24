<?php


namespace App\Controller\espaceClient;

use App\Entity\Meeting;
use App\Form\Handler\MeetingHandler;
use App\Form\MeetingType;
use App\Manager\MeetingManager;
use App\Manager\ParameterManager;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

/**
 * @Route("/espace_client/meeting")
 * Class MeetingController
 * @package App\Controller\espaceClient
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
     * @param PaginatorInterface    $paginator
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
     * @Route("/", name="app_espace_client_meeting_list")
     */
    public function meetingList(Request $request)
    {
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $result = $this->em->getUserMeetingList($this->getUser());
        $meetings = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );

        $meetings->setSortableTemplate('shared/sortable_link_colored.html.twig');
        return $this->render('espace_client/meeting/list.html.twig', [
            'meetings' => $meetings,
            'title' => 'Liste de mes réunions',
            'baseUrl' => $baseurl,
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/detail/{id}",
     *     name="app_espace_client_meeting_detail",
     *     options={"expose"=true},
     *     methods={"GET"})
     *
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function meetingDetail(Request $request, Meeting $meeting)
    {
        $content = $this->em->detailMeeting($meeting);

        return new JsonResponse(
            [
                'body' => $content['content'],
                'footer' => $content['footer'],
                'success' => true,
            ]
        );
    }

    /**
     * @Route("/add", name="app_espace_client_meeting_add")
     * @Route("/edit/{id}", name="app_espace_client_meeting_edit")
     *
     * @param Request       $request
     * @param Meeting|null  $meeting
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function meetingAdd(Request $request, RouterInterface $router, ParameterManager $paramManager, Meeting $meeting = null)
    {
        $meetingEntity = $meeting ?? new Meeting();
        $title = $meeting ? 'Modifier la réunion' : 'Ajouter une réunion';
        $mode = $meeting ?? false;
        $userSubscription = $this->getUser()->getSubscriptionUser();
        if ($userSubscription === null) {
            return $this->redirectToRoute('app_espace_client_subscription_list');
        }

        $form = $this->createForm(MeetingType::class, $meetingEntity);
        $handler = new MeetingHandler($form, $request, $this->getUser(), $this->em, $router);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $meetingInput = $form->getData();
            $restriction = $handler->restriction($meetingInput);
            if (is_bool($restriction)) {
                $handler->onSuccess();
                $this->em->createMeeting($request, $paramManager, $this->urlBbb, $this->secretBbb, $this->getUser());
                if ($meeting) {
                    return $this->redirectToRoute('app_espace_client_meeting_list');
                } else {
                    $theMeeting = $this->em->getUserLastMeeting($this->getUser());
                    $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

                    return $this->render('espace_client/meeting/add_confirmation.html.twig', [
                        'link' => $baseurl.'/reunion/'.$theMeeting->getIdentifiant(),
                        'title' => 'Bravo, vous venez de créer une réunion',
                    ]);
                }
            } else {
                $this->addFlash('error', $restriction);
            }
        }

        return $this->render('espace_client/meeting/add.html.twig', [
            'title' => $title,
            'mode' => $mode,
            'form' => $form->createView(),
            'userSubscription' => $userSubscription,
        ]);
    }

    /**
     * @Route("/delete/{id}",
     *     name="app_epsace_client_meeting_delete",
     *     options={"expose"=true},
     *     methods={"GET"})
     *
     * @param Request $request
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function meetingDelete(Request $request, Meeting $meeting)
    {
        $this->em->endMeeting($meeting, $this->urlBbb, $this->secretBbb);
        $result = $this->em->deleteMeeting($meeting, $this->getUser());
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $meetings = $this->paginator->paginate(
            $result,
            $request->query->getInt('page', 1),
            10
        );
        $meetings->setSortableTemplate('shared/sortable_link.html.twig');

        return new JsonResponse( [
            'listHtml' => $this->renderView('espace_client/meeting/liste_ajax.html.twig', [
                'meetings' => $meetings,
                'baseUrl' => $baseurl,
            ]),
            'body' => "<p>La réunion est bien supprimée.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
