<?php

namespace App\Controller\FrontEnd;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LoginController extends Controller
{
    public function index()
    {
        return $this->render("frontend/connexion/index.html.twig");
    }

}
