<?php

namespace App\Controller;


use App\Entity\Page;
use App\Service\QuestionManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/test/{id}", name="test")
     */
    public function test($id)
    {
        $profil =$this->getUser()->getID();

        $em = $this->getDoctrine()->getManager();


//      $pages = $em->getRepository("App:Page")->findPages($id, $profil);
        $pages = $em->getRepository("App:Page")->findPageBasic($id, $profil);


        echo '<pre>' , var_dump($pages) , '</pre>';

        return $this->render('default/index.html.twig');
    }
}
