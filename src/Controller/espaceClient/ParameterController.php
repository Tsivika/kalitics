<?php


namespace App\Controller\espaceClient;


use App\Entity\Parameter;
use App\Form\Handler\ParamHandler;
use App\Form\ParameterType;
use App\Manager\ParameterManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ParameterController
 *
 * @Route("/espace_client/parameter",
 *     name="app_espace_client_parameter_"
 * )
 *
 * @package App\Controller\espaceClient
 */
class ParameterController extends AbstractController
{
    /**
     * @var ParameterManager
     */
    private $em;

    /**
     * ParameterController constructor.
     *
     * @param ParameterManager $em
     */
    public function __construct(ParameterManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="add" )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function parameterAdd(Request $request)
    {
        $param = is_object($this->em->getParamUser($this->getUser()))  ? $this->em->getParamUser($this->getUser()) : new Parameter();
        $form = $this->createForm(ParameterType::class, $param);
        $handler = new ParamHandler($form, $request, $this->em, $this->getUser());
        if ($handler->process()) {
            echo 'OKOK';
        }

        return $this->render('espace_client/parameter/add.html.twig', [
            'title' => 'Gestion de vos paramètres',
            'form' => $form->createView(),
            'parameters' => $param,
        ]);
    }
}
