<?php

namespace App\Controller;

use App\Entity\Test;
use App\Entity\TestFolder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @Route("/{id}", name="tests_index", requirements={"id"="\d*"})
     * @ParamConverter("test_folder", class="App:TestFolder", isOptional=true)
     */
    public function index(TestFolder $folder = null)
    {
        if(!$folder)
        {
            $em = $this->getDoctrine()->getManager();
            $folders = $em->getRepository('App:TestFolder')->findRootFolders();
            $tests = $em->getRepository('App:Test')->findRootTests();

            $folder = new TestFolder();
            $folder->setName("/");
            $folder->setChildren($folders);
            $folder->setTests($tests);
        }

        return $this->render('tests/index.html.twig', [
            'current_folder' => $folder
        ]);
    }

    /**
     * @Route("/delete_test/{id}", name="tests_delete_test")
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
     * @Route("/delete_folder/{id}", name="tests_delete_folder")
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
     * @Route("/move_test/{test_id}/to_folder/{folder_id}", name="tests_move_test", requirements={"folder_id"="\d*"})
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
     * @Route("/move_folder/{folder_id}/to_folder/{destination_id}", name="tests_move_folder", requirements={"destination_id"="\d*"})
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
     * @Route("/search/{query}", name="tests_search")
     * @param $query
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search($query){
        $em = $this->getDoctrine()->getManager();
        $folders = $em->getRepository("App:TestFolder")->findByName($query);
        $tests = $em->getRepository("App:Test")->findByName($query);

        return $this->render('tests/search.html.twig', [
            'query' => $query,
            'folders' => $folders,
            'tests' => $tests
        ]);
    }
}
