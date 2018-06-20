<?php

namespace App\Controller;


use App\Service\QuestionManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/test/{id}", name="test")
     */
    public function test($id,QuestionManager $questionManager)
    {
        $profil =$this->getUser()->getID();

        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository("App:Question")->findQuestions($id);
        var_dump($questions);
        $answers = $em->getRepository("App:Response")->findAnswers($id, $profil);

        $randomQuestion = $questionManager->randomQuestion($questions, $answers);
        var_dump($randomQuestion);

        return $this->render('default/index.html.twig');
    }
}
