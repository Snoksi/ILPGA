<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class QuestionnaireController extends Controller
{
    /**
     * @Route("/questionnaire", name="test_test")
     */
    public function xlsAction()
    {

        $path = $this->getParameter('excel_directory').'\b920684226ebb0e012b12154ae1fb521.xlsx';

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        return $this->render('upload/excel.html.twig', ['array' => $sheetData]);
    }
}

