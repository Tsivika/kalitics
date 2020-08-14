<?php

namespace App\Controller\espaceClient;

use App\Entity\User;
use App\Form\Handler\ProfilHandler;
use App\Form\ProfilType;
use App\Services\ImageUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Manager\UserManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/espace_client/profil")
 *
 * Class ProfilController
 * @package App\Controller\espaceClient
 */
class ProfilController extends AbstractController
{
    /**
     * @var UserManager
     */
    private $em;

    /**
     * ProfilController constructor.
     * @param UserManager $em
     */
    public function __construct(UserManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/edit/", name="app_espace_client_profil_edit")
     *
     * @param User $user
     *
     * @return Response
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageUploader $imageUploader)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class, $user);
        $handler = new ProfilHandler($form, $request, $passwordEncoder, $user, $this->em, $imageUploader);
        if ($handler->process()) {
            $this->addFlash('success', 'Modification profil prise en compte.');
            return $this->redirectToRoute('app_espace_client_profil_edit');
        }

        return $this->render('espace_client/profil/profil.html.twig', [
            'title' => 'Détail de votre profil',
            'form' => $form->createView(),
            'pdp' => $user->getPdp(),
            'subscription' => $user->getSubscriptionUser(),
        ]);
    }

    /**
     * @Route("/delete/{id}",
     *     name="app_espace_client_profil_delete",
     *     options={"expose"=true},
     *     methods={"get"})
     *
     * @param User $user
     *
     * @return JsonResponse
     */
    public function userDeleteProfil(User $user, TokenStorageInterface $tokenStorage)
    {
        $tokenStorage->setToken(null);
        $this->em->delete($user);

        return new JsonResponse([
            'body' => "<p>Votre compte est bien supprimé sur Hiboo.</p>",
            'footer' => '<span>Consulter notre <a href="" class="text-green"> Politique de confidentialité</a></span>',
            'success' => true,
        ]);
    }
}
