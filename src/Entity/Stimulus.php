<?php

// src/Entity/Excel.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class Stimulus
{


    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the file as a xlsx file.")
     * @Assert\File(
     *     maxSize = "20M",
     *     mimeTypes={"audio/mpeg"}
     * )
     */
    private $stimulus;

//////////////////////////////////////////////////

    /**
     * @return mixed
     */
    public function getStimulus()
    {
        return $this->stimulus;
    }

    /**
     * @param mixed $stimulus
     */
    public function setStimulus($stimulus): void
    {
        $this->stimulus = $stimulus;
    }






}