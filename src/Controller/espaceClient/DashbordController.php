<?php


namespace App\Controller\espaceClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/espace_client")
 * Class DashbordController
 * @package App\Controller\espaceClient
 */
class DashbordController extends AbstractController
{
    /**
     * @Route("/", name="app_espace_client")
     */
    public function index()
    {
        return new Response("Espace Client");
    }
}