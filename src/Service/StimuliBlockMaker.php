<?php

namespace App\Service;

use App\Entity\Block;
use App\Entity\Page;
use App\Entity\Question;
use App\Entity\Stimulus;
use App\Service\ExcelStimuliParser\RowReader;
use App\Service\ExcelStimuliParser\SheetReader;
use App\Service\Uploader\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Intl\Exception\MissingResourceException;

class StimuliBlockMaker
{
    protected $block;

    protected $sheet;

    protected $stimuli = [];

    protected $pages = [];

    protected $lastQuestion;

    protected $uploader;


    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
        $this->block = new Block();
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->getBlock()->getTest();
    }

    /**
     * @param mixed $test
     */
    public function setTest($test): void
    {
        $this->getBlock()->setTest($test);
    }

    /**
     * @return Block
     */
    public function getBlock(): Block
    {
        return $this->block;
    }

    /**
     * @param Block $block
     */
    public function setBlock(Block $block): void
    {
        $this->block = $block;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->getBlock()->setTitle($title);
    }

    /**
     * @param bool $boolean
     */
    public function setRandom($boolean = false)
    {
        $this->getBlock()->setRandom(false);
    }

    /**
     * @param $files
     * @param UploadedFile $excel
     */
    public function import($files, UploadedFile $excel)
    {
        $this->addStimuli($files);
        $this->bindExcel($excel);
    }

    /**
     * Uploads all mp3/mp4 files to a public folder in the test folder
     * @param $files
     */
    public function addStimuli($files)
    {
        if (!$this->block instanceof Block) {
            throw new MissingResourceException("App\Entity\PageGroup is missing");
        }

        $this->uploader->setTargetDirectory("/uploads/tests/" . $this->block->getId());

        foreach ($files as $file) {
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

        foreach ($this->sheet as $row) {
            $stimulusName = $row->getStimulusName();

            var_dump($stimulusName); // If there is no stimulus matching the sheet name, next row
            if (!isset($this->stimuli[$stimulusName])) {
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

            $this->getBlock()->addPage($page);
        }
    }

    private function generateQuestionPage(RowReader $row)
    {
        // Si aucune question n'a été associé à ce stimulus, alors on l'associe à la question précédente
        if ($row->getQuestion() == "") return $this->lastQuestion;

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
        $question->setType($row->getType());
        $question->setOptions($this->generateQuestionOptions($row));

        return $question;
    }

    public function generateQuestionOptions(RowReader $row)
    {
        $type = $row->getType();
        $options = [];

        if ($type == "checkbox" || $type == "radio") {
            $choices = [];
            $col = "H";
            $hasChoices = true;

            while ($hasChoices) {
                $choice = $row->getCell($col);

                if ($choice == "") break;
                $choices[] = $choice;
                $col++;
            }

            $options["choices"] = $choices;
        }

        if ($type == "range") {
            $options['min'] = $row->getCell("H");
            $options['max'] = $row->getCell("I");
        }

        return $options;
    }



}