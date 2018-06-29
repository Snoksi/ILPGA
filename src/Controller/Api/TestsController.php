<?php

namespace App\Controller\Api;

use App\Entity\Block;
use App\Entity\Page;
use App\Entity\Test;
use App\Entity\TestFolder;
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
     * @Rest\Delete("/delete_page/{id}", name="tests_delete_page", defaults={"_format" = "json"})
     * @ParamConverter("page", class="App:Page")
     * @param Page $page
     * @return array
     */
    public function deletePage(Page $page)
    {
        if(!$page){
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($page);
        $em->flush();

        return [
            'success' => true
        ];
    }


    /**
     * @Rest\Delete("/delete_block/{id}", name="tests_delete_block", defaults={"_format" = "json"})
     * @ParamConverter("block", class="App:Block")
     * @param Block $block
     * @return array
     */
    public function deleteBlock(Block $block)
    {
        if(!$block){
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($block);
        $em->flush();

        return [
            'success' => true
        ];
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
     * @Rest\Post("/create_folder/{name}/{destination_id}", name="tests_create_folder", requirements={"destination_id"="\d*"})
     * @ParamConverter("destination", class="App:TestFolder", options={"mapping": {"destination_id": "id"}}, isOptional=true)
     * @param TestFolder|null $destination
     * @return array
     */
    public function createFolder($name, TestFolder $destination = null)
    {
        $folder = new TestFolder();
        $folder->setName($name);
        $folder->setParent($destination);

        $em = $this->getDoctrine()->getManager();
        $em->persist($folder);
        $em->flush();

        return $this->json([
            'success' => true
        ]);
    }
}
