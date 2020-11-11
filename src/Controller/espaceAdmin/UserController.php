<?php

namespace App\Controller\espaceAdmin;

use App\Entity\User;
use App\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UserController
 *
 * @Route("/espace_admin/user", name="app_espace_admin_user")
 *
 * @package App\Controller\espaceAdmin
 */
class UserController extends AbstractController
{
    /**
     * @var UserManager
     */
    private $em;

    /**
     * UserController constructor.
     * @param UserManager $em
     */
    public function __construct(UserManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/delete/{id}",
     *     name="_delete",
     *     options={"expose"=true},
     *     methods={"GET"}
     *     )
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function UserDelete(User $user)
    {
        $this->em->deleteUser($user);

        return new JsonResponse( [
            'body' => "<p>Utilisateur supprimé.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'status' => true,
            'success' => true,
        ]);
    }

    /**
     * @Route("/deactive/{id}",
     *     name="_deactive",
     *     options={"expose"=true},
     *     methods={"GET"}
     *     )
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function UserDeactive(User $user)
    {
        $this->em->deactiveUser($user);

        return new JsonResponse( [
            'body' => "<p>Utilisateur desactivé.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'status' => true,
            'success' => true,
        ]);
    }

    /**
     * @Route("/update_user", name="_update")
     */
    public function userUpdate()
    {
        $this->em->updateUser();

        return new Response('Mis à jour ok');
    }
}
