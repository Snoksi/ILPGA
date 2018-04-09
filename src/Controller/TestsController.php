<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Test;
use App\Entity\TestFolder;
use App\Form\CreatePostTestFormType;
use App\Form\CreateTestType;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
     * @param TestFolder|null $folder
     * @return \Symfony\Component\HttpFoundation\Response
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

    /**
     * @Route("/create", name="tests_create")
     * @Route("/create/in_folder/{id}", name="tests_create_in")
     * @ParamConverter(name="folder", class="App:TestFolder", isOptional=false)
     * @param Request $request
     * @param TestFolder|null $folder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, TestFolder $folder = null)
    {
        $test = new Test();
        $page = new Page();
        $page->setType("page");
        $page->setTest($test);

        $test->setFolder($folder);
        $test->setInstructionPage($page);

        $form = $this->createForm(CreateTestType::class, $test);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($test);
            $em->flush();

            return $this->redirectToRoute('users_index');
        }
        return $this->render('tests/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/create/step/2", name="tests_create_step_2")
     * @param Request $request
     */
    public function step2(Request $request){
        $form = $this->createForm(CreatePostTestFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
        }
        return $this->render('tests/step_2.html.twig', ['form' => $form->createView()]);
    }
}
