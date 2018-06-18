<?php

namespace App\Service;

use App\Entity\Page;
use App\Entity\Question;
use App\Entity\Stimulus;
use App\Entity\Test;
use App\Service\ExcelStimuliParser\RowReader;
use App\Service\ExcelStimuliParser\SheetReader;
use App\Service\Uploader\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Intl\Exception\MissingResourceException;

class TestManager
{
    protected $test;

    protected $sheet;

    protected $stimuli = [];

    protected $pages = [];

    protected $lastQuestion;

    protected $uploader;


    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param Test $test
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Uploads all mp3/mp4 files to a public folder in the test folder
     * @param $files
     */
    public function addStimuli($files){
        if(!$this->test instanceof Test){
            throw new MissingResourceException("App\Entity\Test is missing");
        }

        $this->uploader->setTargetDirectory("/uploads/tests/".$this->test->getId());

        foreach($files as $file){
            $stimulus = new Stimulus();
            $stimulus->setName($file->getClientOriginalName());

            $fileName = $this->uploader->upload($file);
            $stimulus->setSource($fileName);

            $this->stimuli[$stimulus->getName()] = $stimulus;
        }
    }

    /**
     * Read excel and bind informations to the correct stimulus
     * @param UploadedFile $excel
     */
    public function bindExcel(UploadedFile $excel)
    {
        $this->sheet = new SheetReader($excel);

        foreach($this->sheet as $row){
            $stimulusName = $row->getStimulusName();
            var_dump($stimulusName);
            // If there is no stimulus matching the sheet name, next row
            if(!isset($this->stimuli[$stimulusName])){
                continue;
            }

            // Else we fill the stimulus of informations
            $stimulus = $this->stimuli[$stimulusName];

            $stimulus->setSpeakerAge($row->getSpeakerAge());
            $stimulus->setSpeakerGender($row->getSpeakerGender());
            $stimulus->setSpeakerLang($row->getSpeakerLang());
            $stimulus->setPlayCount($row->getPlayCount());

            // And we add the stimulus to the page
            $page = $this->generateQuestionPage($row);
            $page->addStimulus($stimulus);

            $this->getTest()->add($page);
        }
    }

    private function getType($type)
    {
        switch($type)
        {
            case "checkbox":
            case "choix_multiple":
                return "checkbox";
            case "radio":
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

    private function generateQuestionPage(RowReader $row)
    {
        // Si aucune question n'a été associé à ce stimulus, alors on l'associe à la question précédente
        if($row->getQuestion() == "") return $this->lastQuestion;

        $page = new Page();
        $page->setTest($this->getTest());
        $page->setType("question");
        $page->setTitle($row->getQuestion());
        $page->addQuestion($this->generateQuestionFromRow($row));

        $this->lastQuestion = $page;
        return $page;
    }

    private function generateQuestionFromRow(RowReader $row)
    {
        $question = new Question();
        $question->setLabel($row->getQuestion());
        $question->setType($this->getType($row->getType()));
        $question->setOptions($this->generateQuestionOptions($row));

        return $question;
    }

    public function generateQuestionOptions(RowReader $row)
    {
        $type = $row->getType();
        $options = [];

        if ($type == "checkbox" || $type == "radio")
        {
            $choices = [];
            $col = "H";
            $hasChoices = true;

            while($hasChoices) {
                $choice = $row->getCell($col);

                if ($choice == "") exit;
                $choices[] = $choice;
                $col++;
            }

            $options["choices"] = $choices;
        }

        if($type == "range")
        {
            $options['min'] = $row->getCell("H");
            $options['max'] = $row->getCell("I");
        }

        return $options;
    }
}