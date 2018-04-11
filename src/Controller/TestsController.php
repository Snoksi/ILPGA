<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Test;
use App\Entity\TestFolder;
use App\Form\CreatePostTestFormType;
use App\Form\CreateTestType;
use App\Form\StimuliAndQuestionsFormType;
use App\Service\Uploader\FileUploader;
use App\Service\Uploader\StimulusUploader;
use App\Utils\FormGenerator\PreTestFormSerializer;
use Doctrine\ORM\Mapping\Entity;
use FOS\RestBundle\Controller\Annotations as Rest;
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
     * @Route("/create/step/{step}", name="tests_create_step")
     * @ParamConverter(name="folder", class="App:TestFolder", isOptional=false)
     * @param SessionInterface $session
     * @param TestFolder|null $folder
     * @param int $step
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(SessionInterface $session, TestFolder $folder = null, $step = 1)
    {
        $reachedStep = $session->get('reached_step', 1);

        if($step > $reachedStep){
            throw new AccessDeniedException();
        }

        if($step == 1){
            return $this->forward("App\Controller\TestsController::step1", [
                'folder' => $folder
            ]);
        }

        $testId = $session->get('editing_test_id');
        $em = $this->getDoctrine()->getManager();
        $test = $em->getRepository('App:Test')->find($testId);

        return $this->forward("App\Controller\TestsController::step".$step, [
            'test' => $test
        ]);
    }

    /**
     * @param Request $request
     * @param TestFolder|null $folder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function step1(Request $request, SessionInterface $session, TestFolder $folder = null)
    {
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

            $session->set('editing_test_id', $test->getId());

            return $this->redirectToStep(2);
        }
        return $this->render('tests/step_1.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step2(Request $request, SessionInterface $session, Test $test){
        $form = $this->createForm(CreatePostTestFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $formSerializer = new PreTestFormSerializer($form->getData());

            $page = new Page();
            $page->setTitle("Formulaire pre-test");
            $page->setType('form');
            $page->setContent($formSerializer->getSerializedForm());
            $page->setTest($test);

            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            return $this->redirectToStep(3);
        }
        return $this->render('tests/step_2.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function step3(Request $request, SessionInterface $session, StimulusUploader $uploader, Test $test){
        $form = $this->createForm(StimuliAndQuestionsFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $input = $form->getData();

            $uploader->setTest($test);
            $uploader->upload($input['audio']);
            $uploader->bind($input['excel']);
            $stimuli = $uploader->getStimuli();

            $em = $this->getDoctrine()->getManager();

            foreach($stimuli as $stimulus){
                $em->persist($stimulus);
            }
            $em->flush();
        }
        return $this->render('tests/step_3.html.twig', ['form' => $form->createView()]);
    }

    public function redirectToStep($step = 1){
        $this->get('session')->set('reached_step', $step);
        return $this->redirectToRoute('tests_create_step', ['step' => $step]);
    }

}
