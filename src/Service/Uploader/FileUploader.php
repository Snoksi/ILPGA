<?php

namespace App\Service\Uploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($publicDirectory)
    {
        $this->publicDirectory = $publicDirectory;
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
        $file->move($this->getTargetDirectory(), $fileName);
        return $fileName;
    }

    public function setTargetDirectory($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

}