<?php

namespace App\Controller;


use App\Entity\Page;
use App\Service\QuestionManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/test/{username}/{name_test}", name="test")
     */
    public function test($username, $name_test)
    {
        $profil =$this->getUser()->getID();

        $em = $this->getDoctrine()->getManager();
        $username = $em->getRepository('App:User')->findIdOnly($username);

        $id = $em->getRepository("App:Test")->findIdTest($username, $name_test);
//        var_dump($id);
        $pages = $em->getRepository("App:Page")->findPagesSql($id, $profil);

        echo '<pre>' , var_dump($pages) , '</pre>';


        return $this->render('default/index.html.twig');



    }
}
