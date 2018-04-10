<?php
// src/Controller/UploadController.php
namespace App\Controller;

use App\Entity\Stimulus;
use App\Form\Upload\StimulusType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Excel;
use App\Form\Upload\ExcelType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;


class UploadController extends Controller
{
    /**
     * @Route("/upload_audio", name="upload_audio")
     */
    public function news(Request $request)
    {
        $stimulus = new Stimulus();
        $form = $this->createForm(StimulusType::class, $stimulus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded mp3 file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $stimulus->getStimulus();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $username = $this->getUser()->getUsername();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('audio_directory'). $username .'/audio/',
                $fileName
            );
            $stimulus->setUser($this->getUser()->getId());
            $stimulus->setName($fileName);
            $stimulus->setSource($this->getParameter('audio_directory').$username.'/audio/');
            $stimulus->setTest('politic');
            //Save modification in the database
            $stimulus->setStimulus($file->getClientOriginalName());

            // ... persist the $product variable or any other work
            $em = $this->getDoctrine()->getManager();
            $em->persist($stimulus);
            $em->flush();


            return $this->redirectToRoute('upload_excel');
        }

        return $this->render('upload/stimulus.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/upload_excel", name="upload_excel")
     */
    public function new(Request $request)
    {
        $excel = new Excel();
        $form = $this->createForm(ExcelType::class, $excel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $excel->getExcel();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $username = $this->getUser()->getUsername();
            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('excel_directory'). $username .'/excel/',
                $fileName
            );

            $excel->setUser($this->getUser()->getId());
            $excel->setName($file->getClientOriginalName());
            $excel->setSource($this->getParameter('excel_directory').$username.'/excel/');
            $excel->setTest('politic');
            //Save modification in the database
            $excel->setExcel($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($excel);
            $em->flush();

            // ... persist the $product variable or any other work
            $path = $this->getParameter('excel_directory'). $username .'/excel/'.$fileName;

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $test = $spreadsheet->getActiveSheet()->getCell('A2');
            $name = $spreadsheet->getActiveSheet()->getCell('B2');

            $em = $this->getDoctrine()->getManager();
            $test_table = $em->getRepository("App:Stimulus")->findOneBy([
                'stimulus' => $name,
                'test' => $test,
            ]);
            if (!empty($test_table)) {
                $test_table->setAge($spreadsheet->getActiveSheet()->getCell('C2'));
                $test_table->setSexe($spreadsheet->getActiveSheet()->getCell('D2'));
                $test_table->setLangue($spreadsheet->getActiveSheet()->getCell('E2'));
                $test_table->setQuestion($spreadsheet->getActiveSheet()->getCell('G2'));
                $em = $this->getDoctrine()->getManager();
                $em->persist($test_table);
                $em->flush();
                echo 'bravo';
            }else { echo 'vide';}


            // send it to service to stock the info as a json
            return $this->render('upload/resultUpload.html.twig', ['array' => $sheetData]);

        }

        return $this->render('upload/excel.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}