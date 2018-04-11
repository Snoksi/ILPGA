<?php

namespace App\Service\Uploader;

use App\Entity\Page;
use App\Entity\Stimulus;
use App\Entity\Test;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Intl\Exception\MissingResourceException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class StimulusUploader
{
    protected $test;

    protected $stimuli = [];

    protected $questions = [];

    protected $lastQuestion;

    protected $uploader;


    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Uploads all mp3/mp4 files to a public folder in the test folder
     * @param $files
     */
    public function upload($files){
        if(!$this->test instanceof Test){
            throw new MissingResourceException("App\Entity\Test is missing");
        }

        $this->uploader->setTargetDirectory("/uploads/tests/".$this->test->getId());

        foreach($files as $file){
            $stimulus = new Stimulus();
            $stimulus->setName($file->getClientOriginalName());

            $fileName = $this->uploader->upload($file);
            $stimulus->setSource($this->uploader->getTargetDirectory()."/".$fileName);

            $this->stimuli[$file->getClientOriginalName()] = $stimulus;
        }
    }

    /**
     *  Read excel and bind informations to the correct stimulus
     */
    public function bind(UploadedFile $excel){
        $sheet = $this->getSheetReader($excel);

        $row = 2;
        while(true) {
            $stimulusName = $sheet->getCell("A" . $row)->getValue();

            // If there is no stimulus, stops the parsing
            if (!isset($this->stimuli[$stimulusName]) && $stimulusName == "") break;

            // Else we fill the stimulus of informations
            $stimulus = $this->stimuli[$stimulusName];

            $stimulus->setSpeakerAge($sheet->getCell("B" . $row)->getValue());
            $stimulus->setSpeakerGender($sheet->getCell("C" . $row)->getValue());
            $stimulus->setSpeakerLang($sheet->getCell("D" . $row)->getValue());
            $stimulus->setPlayCount($sheet->getCell("E" . $row)->getValue());

            $question = $this->generateQuestionPage($row, $sheet);
            $question->addStimulus($stimulus);

            $this->questions[] = $question;

            $row++;
        }
        var_dump("Bind end");
    }

    public function getQuestions()
    {
        return $this->questions;
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
        $question->setTitle($sheet->getCell("F" . $row)->getValue());

        $question->setContent($this->generateQuestionContent($row, $sheet));
        $question->lastQuestion = $question;
        return $question;
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

    /**
     * @param $excel
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    private function getSheetReader($excel){
        try{
            $inputFileType = IOFactory::identify($excel->getRealPath());
            /**  Create a new Reader of the type that has been identified  **/
            $reader = IOFactory::createReader($inputFileType);
            /**  Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($excel);
        }
        catch(\Exception $exception){
            throw new BadRequestHttpException("Inccorrect Excel file");
        }

        return $spreadsheet->getActiveSheet();
    }
}