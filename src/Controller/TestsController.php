<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TestsController
 * @package App\Controller
 * @Route("/tests");
 */
class TestsController extends Controller
{
    /**
     * @Route("/", name="tests_index")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $folders = $em->getRepository('App:TestFolder')->findAll();

        return $this->render('tests/index.html.twig', [
            'folders' => $folders
        ]);
    }

    /**
     * @Route("/delete", name="tests_delete")
     */
}
