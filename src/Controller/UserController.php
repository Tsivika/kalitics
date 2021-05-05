<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Manager\UserManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * UserController constructor.
     * @param UserManager $em
     */
    public function __construct(UserManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/new", name="create_user")
     *
     * @Route("/edit/{user}", name="update_user")
     *
     * @param Request $request
     * @param User|null $user
     * @return RedirectResponse|Response
     */
    public function userCreate(Request $request, User $user = null)
    {
        try {
            $title = $user ? 'Modification utilisateur' : 'Ajout utilisateur';
            $user = $user ?? new User();
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->saveUser($user);
                $this->addFlash('success', 'Enregistrement pris en compte.');

                return $this->redirectToRoute('list_user');
            }

            return $this->render('user/add.html.twig', [
                'title' => $title,
                'form' => $form->createView(),
            ]);
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Erreur lors de la création de l\'utilisateur.');
        }
    }

    /**
     * @Route("/list", name="list_user")
     */
    public function userList(): Response
    {
        $users = $this->em->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
            'title' => 'Liste des utilisateurs',
        ]);
    }

    /**
     * @Route("/detail/{user}", name="detail_user")
     * @param User $user
     * @return Response
     */
    public function userDetail(User $user)
    {
        return $this->render('user/detail.html.twig', [
            'user' => $user,
            'title' => 'Détail utilisateur',
        ]);
    }

    /**
     * @Route("/delete/{user}", name="delete_user")
     * @param User $user
     * @return RedirectResponse
     */
    public function userDelete(User $user)
    {
        $this->em->delete($user);
        $this->addFlash('error', 'Utilisateur supprimé.');

        return $this->redirectToRoute('list_user');
    }
}
