<?php

namespace App\Controller\Api;

use App\Entity\Test;
use App\Entity\TestFolder;
use App\Entity\Page;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class TestsController
 * @package App\Controller
 * @Route("/api/tests");
 */
class TestsController extends Controller
{

    /**
     * @Rest\Delete("/delete_test/{id}", name="tests_delete_test", defaults={"_format" = "json"})
     * @ParamConverter("test", class="App:Test")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteTest(Test $test)
    {
        if(!$test){
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($test);
        $em->flush();

        return $this->redirectToRoute('tests_index');
    }

    /**
     * @Rest\Delete("/delete_folder/{id}", name="tests_delete_folder", defaults={"_format" = "json"})
     * @ParamConverter("test_folder", class="App:TestFolder")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteFolder(TestFolder $folder)
    {
        if(!$folder){
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($folder);
        $em->flush();

        return $this->redirectToRoute('tests_index');
    }

    /**
     * @Rest\Put("/move_test/{test_id}/to_folder/{folder_id}", name="tests_move_test", requirements={"folder_id"="\d*"}, defaults={"_format" = "json"})
     * @ParamConverter("test", class="App:Test", options={"mapping": {"test_id": "id"}})
     * @ParamConverter("folder", class="App:TestFolder", options={"mapping": {"folder_id": "id"}}, isOptional=true)
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function moveTest(Test $test, TestFolder $folder = null)
    {
        $em = $this->getDoctrine()->getManager();
        $test->setFolder($folder);
        $em->persist($test);
        $em->flush();

        return $this->redirectToRoute('tests_index');
    }

    /**
     * @Rest\Put("/move_folder/{folder_id}/to_folder/{destination_id}", name="tests_move_folder", requirements={"destination_id"="\d*"}, defaults={"_format" = "json"})
     * @ParamConverter("folder", class="App:TestFolder", options={"mapping": {"folder_id": "id"}})
     * @ParamConverter("destination", class="App:TestFolder", options={"mapping": {"destination_id": "id"}}, isOptional=true)
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function moveFolder(TestFolder $folder, TestFolder $destination = null)
    {
        $em = $this->getDoctrine()->getManager();
        $folder->setParent($destination);
        $em->persist($folder);
        $em->flush();

        return $this->redirectToRoute('tests_index');
    }

    /**
     * @Rest\Get("/page/{page_id}/set_position/{position}", name="tests_move_position", requirements={"folder_id"="\d*"}, defaults={"_format" = "json"})
     * @ParamConverter("page", class="App:Page", options={"mapping": {"page_id": "id"}})
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function movePosition(Page $page, $position)
    {
        $pos_init = $page->getPosition();
        if ($pos_init < $position) {
            for ($i = $pos_init; $i< $position; $i++){
                $em = $this->getDoctrine()->getManager();
                $search = $em->getRepository('App:Page')->findOneBy(['position' => $i+1]);
                $search->setPosition($i);
                $em->persist($search);
                $em->flush();
            }
        }else{
            for ($i = $pos_init; $i > $position; $i--){
                $em = $this->getDoctrine()->getManager();
                $search = $em->getRepository('App:Page')->findOneBy(['position' => $i-1]);
                $search->setPosition($i);
                $em->persist($search);
                $em->flush();
            }
        }
        $page->setPosition($position);
        $em->persist($page);
        $em->flush();
    }

}
