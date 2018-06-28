<?php

namespace App\Controller;



use http\Env\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    /**
     * @Route("/test/{creator}/{name_test}", name="test")
     */
    public function test($creator, $name_test)
    {
        $profil =$this->getUser()->getID();
        $em = $this->getDoctrine()->getManager();
        $creator = $em->getRepository('App:User')->findIdOnly($creator);
        $test = $em->getRepository("App:Test")->findTest($creator, $name_test);
        $isRandomBlock = $test[0]['random'];
        $idTest = $test[0]['id'];
        $pages = $em->getRepository("App:Page")->findPagesSql($idTest, $profil);
        if (!empty($pages)){
            if ($isRandomBlock) {
                var_dump('RANDOMIZATION !!!');
                $pages = $em->getRepository("App:Page")->findPagesSql($idTest, $profil);
                $randomPage = $pages[array_rand($pages)];

                $page = $em->getRepository("App:Page")->getInfosPage($randomPage);
                $stimulus = $em->getRepository("App:Stimulus")->findStimulus($randomPage);
                $questions = $em->getRepository("App:Question")->findBy(['page' => $randomPage]);
                return $this->render('default/index.html.twig', ['questions' => $questions, 'page' => $page, 'stimulus' => $stimulus]);
            }

            $pages = $em->getRepository("App:Page")->findPagesSql($idTest, $profil);

            $controlledPage = $pages[0]['id'];

            $page = $em->getRepository("App:Page")->getInfosPage($controlledPage);
            $questions = $em->getRepository("App:Question")->findQuestionsPage($controlledPage);
            $stimulus = $em->getRepository("App:Stimulus")->findStimulus($controlledPage);

            return $this->render('default/index.html.twig', ['questions' => $questions, 'page' => $page, 'stimulus' => $stimulus]);
        }
        return $this->redirectToRoute('end_pages');
    }

    /**
     * @Route("/test/end", name="end_pages")
     */
    public function endPages($creator, $name_test)
    {
        return $this->render('default/congratz.html.twig');
    }
//
//    /**
//     * @Route("/test/{creator}/{name_test}/answer", name="validate_answer")
//     */
//    public function registerAnswers(Request $request)
//    {
//
//        return $this->redirectToRoute('test');
//    }
}
