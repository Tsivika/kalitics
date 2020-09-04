<?php


namespace App\Controller\espaceClient;

use App\Entity\Meeting;
use App\Form\Handler\MeetingHandler;
use App\Form\MeetingType;
use App\Manager\MeetingManager;
use App\Manager\ParameterManager;
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
     * MeetingController constructor.
     * @param MeetingManager $em
     */
    public function __construct(MeetingManager $em, ContainerBagInterface $params)
    {
        $this->em = $em;
        $this->params = $params;
        $this->urlBbb = $this->params->get('app.bbb_server_base_url');
        $this->secretBbb = $this->params->get('app.bbb_secret');
    }

    /**
     * @Route("/", name="app_espace_client_meeting_list")
     */
    public function meetingList()
    {
        $meetings = $this->em->getUserMeetingList($this->getUser());

        return $this->render('espace_client/meeting/list.html.twig', [
            'meetings' => $meetings,
            'title' => 'Liste de mes réunions',
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
//        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
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

        $form = $this->createForm(MeetingType::class, $meetingEntity);
        $handler = new MeetingHandler($form, $request, $this->getUser(), $this->em, $router);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $meetingInput = $form->getData();
            $restriction = $handler->restriction($meetingInput);
            if (is_bool($restriction)) {
                $handler->onSuccess();
                $handler->createMeeting($paramManager, $this->urlBbb, $this->secretBbb);
                if ($meeting) {
                    return $this->redirectToRoute('app_espace_client_meeting_list');
                } else {
                    $theMeeting = $this->em->getUserLastMeeting($this->getUser());

                    return $this->render('espace_client/meeting/add_confirmation.html.twig', [
                        'link' => $theMeeting->getLink(),
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
     * @param Meeting $meeting
     *
     * @return JsonResponse
     */
    public function meetingDelete(Meeting $meeting)
    {
        $this->em->endMeeting($meeting, $this->urlBbb, $this->secretBbb);
        $meetings = $this->em->deleteMeeting($meeting, $this->getUser());

        return new JsonResponse( [
            'listHtml' => $this->renderView('espace_client/meeting/liste_ajax.html.twig', [
                'meetings' => $meetings,
            ]),
            'body' => "<p>La réunion est bien supprimée.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
