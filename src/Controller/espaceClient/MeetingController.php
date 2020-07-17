<?php


namespace App\Controller\espaceClient;

use App\Entity\Meeting;
use App\Form\Handler\MeetingHandler;
use App\Form\MeetingType;
use App\Manager\MeetingManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * MeetingController constructor.
     * @param MeetingManager $em
     */
    public function __construct(MeetingManager $em)
    {
        $this->em = $em;
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
    public function detail(Request $request, Meeting $meeting)
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
     * @param Request $request
     * @param Meeting|null $meeting
     * @return Response
     */
    public function meetingAdd(Request $request, Meeting $meeting = null)
    {
        $meetingEntity = $meeting ?? new Meeting();
        $title = $meeting ? 'Modifier la réunion' : 'Ajouter une réunion';
        $mode = $meeting ?? false;

        $form = $this->createForm(MeetingType::class, $meetingEntity);
        $handler = new MeetingHandler($form, $request, $this->getUser(), $this->em);
        if ($handler->process()) {
            return $this->redirectToRoute('app_espace_client_meeting_list');
        }

        return $this->render('espace_client/meeting/add.html.twig', [
            'title' => $title,
            'mode' => $mode,
            'form' => $form->createView(),
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
    public function deleteMeeting(Meeting $meeting)
    {
        $this->em->delete($meeting);

        return new JsonResponse( [
            'body' => "<p>La réunion est bien supprimée.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
