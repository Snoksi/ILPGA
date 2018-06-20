<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Stimulus;
use App\Entity\Test;
use App\Entity\TestFolder;
use App\Form\PreTestFormType;
use App\Form\CreateTestType;
use App\Form\StimuliAndQuestionsFormType;
use App\Service\TestManager;
use App\Service\Uploader\ExcelParser;
use App\Service\Uploader\FileUploader;
use App\Service\Uploader\StimulusUploader;
use Doctrine\ORM\Mapping\Entity;
use FOS\RestBundle\Controller\Annotations as Rest;
use phpDocumentor\Reflection\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
     * @Route("/", name="tests_index")
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
     * @Route("/search", name="tests_search")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request){
        $query = $request->get('query');

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
     * @param Request $request
     * @param TestFolder|null $folder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createTest(Request $request, FileUploader $uploader)
    {
        $folder = $request->get('folder') ?? null;
        $test = new Test();
        $page = new Page();

        $page->setTitle("Page d'instructions");
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

            return $this->redirectToRoute('tests_create_form', ['test_id' => $test->getId()]);
        }
        return $this->render('tests/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{test_id}/form/create", name="tests_create_form")
     * @ParamConverter("test", class="App:Test", options={"mapping": {"test_id": "id"}})
     * @param Request $request
     * @param Test $test
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createTestForm(Request $request, Test $test)
    {
        $form = $this->createForm(PreTestFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $input = $form->getData();
            $questions = array_merge($input['optional_questions'], $input['questions']);

            $page = new Page();
            $page->setTest($test);
            $page->setTitle($input['title']);
            $page->setQuestions($questions);

            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToRoute('tests_create_stimuli', ['test_id' => $test->getId()]);
        }
        return $this->render('tests/form_create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{test_id}/stimuli/create", name="tests_create_stimuli")
     * @ParamConverter("test", class="App:Test", options={"mapping": {"test_id": "id"}})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createStimuliBlock(Request $request, TestManager $testManager, Test $test){
        $form = $this->createForm(StimuliAndQuestionsFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $input = $form->getData();

            $testManager->setTest($test);
            $testManager->addStimuli($input['audio']);
            $testManager->bindExcel($input['excel']);

            // Updates the test
            $em = $this->getDoctrine()->getManager();
            $em->persist($test);
            $em->flush();

            die();

            return $this->redirectToRoute('tests_get_link', ['id' => $test->getId()]);
        }
        return $this->render('tests/step_3.html.twig', ['form' => $form->createView()]);
    }

    public function redirectToStep($step = 1){
        $this->get('session')->set('reached_step', $step);
        return $this->redirectToRoute('tests_create_step', ['step' => $step]);
    }

    /**
     * @Route("/edit/{id}/get_link", name="tests_get_link")
     * @ParamConverter("test", class="App:Test")
     * @param Request $request
     * @param Test $test
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getTestLink(Request $request, Test $test){
        $domain = $request->getSchemeAndHttpHost();
        $link = $domain.$this->generateUrl('test', ['id' => $test->getId()]);
        return $this->render('tests/get_test_link.html.twig', ['link' => $link]);
    }

}
