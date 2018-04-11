<?php

namespace App\Service\Uploader;

use App\Entity\Stimulus;
use App\Entity\Test;
use Doctrine\ORM\Mapping\Entity;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StimulusUploader extends FileUploader
{
    protected $test;

    protected $stimuli = [];


    public function upload($files){
        foreach($files as $file){
            $stimulus = new Stimulus();
            $stimulus->setTest($this->getTest());
            $stimulus->setName($file->getClientOriginalName());
            $fileName = parent::upload($file);
            $stimulus->setSource($this->getTargetDirectory()."/".$this->getTest()->getId()."/".$fileName);

            $this->stimuli[$file->getClientOriginalName()] = $stimulus;
        }
    }

    public function bind(UploadedFile $excel){
        $excel->getRealPath();

        $inputFileType = IOFactory::identify($excel);
        /**  Create a new Reader of the type that has been identified  **/
        $reader = IOFactory::createReader($inputFileType);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($excel);

        $sheet = $spreadsheet->getActiveSheet();

        $row = 2;
        while($finished === true){
            $stimulusName = $sheet->getCell($col."A")->getValue();
            if("" != $stimulus = $this->stimuli[$stimulusName]){
                exit;
            }

            $stimulus->setAge()

            $row++;
        }

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
    }

    public function getStimuli()
    {
        return $this->stimuli;
    }

    public function setTest(Test $test)
    {
        $this->test = $test;
    }

    public function getTest()
    {
        return $this->test;
    }
}