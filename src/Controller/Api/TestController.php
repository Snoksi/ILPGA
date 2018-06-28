<?php

namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;


class TestController extends Controller
{

    /**
     * @Rest\Put("/test/next", name="next_page")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function moveTest()
    {
        // le nom du test
        $test_id = 'politique montreuil';
        // l'id du profil
        $profil = 1;

        $user = $this->getUser();
        var_dump($user);
        $em = $this->getDoctrine()->getManager();
        // get all the page about a test
        $questions = $em->getRepository("App:page")->findBy(['test_id' => $test_id]);
        // get all the page answered by a profil about a test
        $answers = $em->getRepository("App:response")->findBy(['test_id' => $test_id, 'profil' => $profil]);

        foreach ($questions as &$question) {
            foreach ($answers as &$answer) {
                if ($question->getId() != $answer->getPage()){
                    $result [] = $question;
                }
            }
        }
        $compteur = count($result);
        if ($compteur != 0){
            $random = rand(5, $compteur);
            return $this->redirectToRoute('test', ['question' => $result[$random]]);
        } else {
            // if the table is empty that means the end of the test
            return $this->redirectToRoute('test_end');
        }
    }
    /**
     * @Route("/test/end", name="test_end")
     */
    public function endTest(Request $request)
    {
        $answer = $request->get('country');
        // if ($form->isSubmitted() && $form->isValid())
        if (!empty($answer)){


            return $this->redirectToRoute('next_page');
        }
        return $this->render('questions/end.html.twig');
    }
}