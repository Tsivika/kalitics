<?php

namespace App\Controller;

use App\Entity\Chantier;
use App\Entity\Pointing;
use App\Entity\User;
use App\Form\PointingType;
use App\Manager\PointingManager;
use App\Manager\UserManager;
use App\Services\DateActions;
use App\Services\DateConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/pointing")
 * Class PointingController
 * @package App\Controller
 */
class PointingController extends AbstractController
{
    /**
     * PointingController constructor.
     * @param PointingManager $em
     */
    public function __construct(PointingManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/new", name="create_pointing")
     *
     * @Route("/edit/{pointing}", name="update_pointing")
     *
     * @param Request $request
     * @param Pointing|null $pointing
     * @return RedirectResponse|Response
     */
    public function pointingCreate(Request $request, Pointing $pointing = null)
    {
        $title = $pointing ? 'Modification pointage' : 'Ajout pointage';
        $pointing = $pointing ?? new Pointing();
        $form = $this->createForm(PointingType::class, $pointing);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->savePointing($pointing);
            $this->addFlash('success', 'Enregistrement pris en compte.');

            return $this->redirectToRoute('list_pointing');
        }

        return $this->render('pointing/add.html.twig', [
            'title' => $title,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list", name="list_pointing")
     */
    public function pointingList(): Response
    {
        $pointings = $this->em->findAll();

        return $this->render('pointing/index.html.twig', [
            'pointings' => $pointings,
            'title' => 'Liste des pointage',
        ]);
    }

    /**
     * @Route("/delete/{pointing}", name="delete_pointing")
     * @param Pointing $pointing
     * @return RedirectResponse
     */
    public function pointingDelete(Pointing $pointing): RedirectResponse
    {
        $this->em->delete($pointing);
        $this->addFlash('error', 'Pointage supprimé.');

        return $this->redirectToRoute('list_pointing');
    }

    /**
     * @Route("/daily_user",
     *     name="daily_user_pointing",
     *     options={"expose"=true},
     *     methods={"post"})
     *
     * @param Request $request
     * @param DateConverter $dateConverter
     * @return JsonResponse|Response
     * @throws \Exception
     */
    public function dailyUser(Request $request, DateConverter $dateConverter)
    {
        $message = '';
        $data = json_decode($request->getContent(), true);
        $dailyUser = count($this->em->dailyUser($data, $dateConverter));
        if ($dailyUser > 0) {
            $message = 'Utilisateur déjà pointé pour ce chantier pour cette date!';
        }

        return new JsonResponse([
            'title' => 'Validation',
            'body' => $message,
            'footer' => '',
            'success' => true,
        ]) ;
    }

    /**
     * @Route("/weekly_user",
     *     name="weekly_user_pointing",
     *     options={"expose"=true},
     *     methods={"post"})
     *
     * @param Request $request
     * @param UserManager $userManager
     * @param DateActions $dateActions
     * @return JsonResponse|Response
     */
    public function weeklyUser(Request $request, UserManager $userManager, DateActions $dateActions)
    {
        $data = json_decode($request->getContent(), true);
        $message = $this->em->weeklyUser($data, $userManager, $dateActions);

        return new JsonResponse([
            'title' => 'Validation',
            'body' => $message,
            'footer' => '',
            'success' => true,
        ]) ;

    }
}
