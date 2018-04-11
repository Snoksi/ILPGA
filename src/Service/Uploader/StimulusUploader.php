<?php

namespace App\Service\Uploader;

use App\Entity\Page;
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

    protected $lastQuestion;


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
        $finished = false;

        while(!$finished) {
            $col = "A";
            $stimulusName = $sheet->getCell("A" . $row)->getValue();

            if ("" != $stimulus = $this->stimuli[$stimulusName]) {
                exit;
            }

            $question = $this->generateQuestionPage($row, $sheet);
            $question->setStimulus($stimulus);

            $stimulus->setSpeakerAge($sheet->getCell("B" . $row)->getValue());
            $stimulus->setSpeakerGender($sheet->getCell("C" . $row)->getValue());
            $stimulus->setSpeakerLang($sheet->getCell("D" . $row)->getValue());
            $stimulus->setPlayCount($sheet->getCell("E" . $row)->getValue());
        }
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

    private function getType($type)
    {
        switch($type)
        {
            case "choix_multiple":
                return "checkbox";
            case "choix_unique":
                return "radio";
            case "nombre":
                return "number";
            case "range":
                return "range";
            default:
                return "text";
        }
    }

    private function generateQuestionPage($row, $sheet)
    {
        // Si aucune question n'a été associé à ce stimulus, alors on l'associe à la question précédente
        if($sheet->getCell("F".$row)->getValue() == "") return $this->lastQuestion;

        $question = new Page();
        $question->setTest($this->getTest());
        $question->setType("question");

        $question->setContent($this->generateQuestionContent($row, $sheet));
        $question->lastQuestion = $page;
        return $page;
    }

    private function generateQuestionContent($row, $sheet)
    {
        $content = [
            "label" => $sheet->getCell("F" . $row)->getValue(),
            "type" => $this->getType($sheet->getCell("G" . $row)->getValue()),
        ];

        if ($content['type'] == "checkbox" || $content['type'] == "radio")
        {
            $choices = [];
            $col = "H";
            $has_choices = true;

            while ($has_choices) {
                $choice = $sheet->getCell($col . $row)->getValue();

                if ($choice == "") exit;
                $choices[$choice] = $choice;
                $col++;
            }

            $content["choices"] = $choices;
        }

        if($content['type'] == "range")
        {
            $content['min'] = $sheet->getCell("H".$row)->getValue();
            $content['max'] = $sheet->getCell("I".$row)->getValue();
        }

        return $content;
    }
}