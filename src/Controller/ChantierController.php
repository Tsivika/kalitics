<?php

namespace App\Controller;

use App\Entity\Chantier;
use App\Form\ChantierType;
use App\Manager\ChantierManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/chantier")
 * Class ChantierController
 * @package App\Controller
 */
class ChantierController extends AbstractController
{
    /**
     * ChantierController constructor.
     * @param ChantierManager $em
     */
    public function __construct(ChantierManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/new", name="create_chantier")
     *
     * @Route("/edit/{chantier}", name="update_chantier")
     *
     * @param Request $request
     * @param Chantier|null $chantier
     * @return RedirectResponse|Response
     */
    public function chantierCreate(Request $request, Chantier $chantier = null)
    {
        try {
            $title = $chantier ? 'Modification chantier' : 'Ajout chantier';
            $chantier = $chantier ?? new Chantier();
            $form = $this->createForm(ChantierType::class, $chantier);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->saveChantier($chantier);
                $this->addFlash('success', 'Enregistrement pris en compte.');

                return $this->redirectToRoute('list_chantier');
            }

            return $this->render('chantier/add.html.twig', [
                'title' => $title,
                'form' => $form->createView(),
            ]);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Erreur lors de la création de chantier.');
        }
    }

    /**
     * @Route("/list", name="list_chantier")
     */
    public function chantierList(): Response
    {
        $chantiers = $this->em->findAll();

        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantiers,
            'title' => 'Liste des chantiers',
        ]);
    }

    /**
     * @Route("/detail/{chantier}", name="detail_chantier")
     * @param Chantier $chantier
     * @return Response
     */
    public function chantierDetail(Chantier $chantier): Response
    {
        $totalUser = $this->em->getTotalUser($chantier);
        $totalHour = $this->em->getTotalHourUser($chantier);
        $listUsers = $this->em->getListUser($chantier);
        return $this->render('chantier/detail.html.twig', [
            'chantier' => $chantier,
            'title' => 'Détail chantier',
            'totalUser' => $totalUser,
            'totalHour' => $totalHour,
            'listUsers' => $listUsers,
        ]);
    }

    /**
     * @Route("/delete/{chantier}", name="delete_chantier")
     * @param Chantier $chantier
     * @return RedirectResponse
     */
    public function chantierDelete(Chantier $chantier): RedirectResponse
    {
        $this->em->delete($chantier);
        $this->addFlash('error', 'Chantier supprimé.');

        return $this->redirectToRoute('list_chantier');
    }
}
