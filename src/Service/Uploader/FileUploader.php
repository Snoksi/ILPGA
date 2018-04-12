<?php

namespace App\Service\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    private $rootDirectory;

    public function __construct($rootDirectory, $targetDirectory = null)
    {
        $this->setRootDirectory($rootDirectory);
        $this->setTargetDirectory($targetDirectory);
    }

    public function upload($file)
    {
        if(is_array($file)){
            foreach($file as $f) $this->upload($f);
        }

        if(!$file instanceof UploadedFile){
            throw new \InvalidArgumentException("Must be an instance of UploadedFile");
        }

        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getFullPath(), $fileName);
        return $fileName;
    }

    public function getFullPath(){
        return $this->getRootDirectory().$this->getTargetDirectory();
    }

    public function setTargetDirectory($targetDirectory = null)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * @return mixed
     */
    public function getRootDirectory()
    {
        return $this->rootDirectory;
    }

    /**
     * @param mixed $rootDirectory
     */
    public function setRootDirectory($rootDirectory): void
    {
        $this->rootDirectory = $rootDirectory;
    }

}