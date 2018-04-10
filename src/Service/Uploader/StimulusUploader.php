<?php

namespace App\Service\Uploader;

use App\Entity\Stimulus;
use App\Entity\Test;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class StimulusUploader extends FileUploader
{
    protected $test;

    protected $stimuli = [];

    public function __construct($projectDirectory)
    {
        $this->projectDirectory = $projectDirectory;
    }

    public function upload($files){
        foreach($files as $file){
            $stimulus = new Stimulus();
            $stimulus->setTest($this->getTest());
            $stimulus->setName($file->getClientOriginalName());
            $fileName = $this->upload($file);
            $stimulus->setSource($this->getTargetDirectory()."/".$fileName);

            $this->stimuli[$file->getClientOriginalName()] = $stimulus;
        }
    }

    public function bind($excel){

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