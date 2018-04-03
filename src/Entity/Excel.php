<?php

// src/Entity/Excel.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class Excel
{
    // ...

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the file as a xlsx file.")
     * @Assert\File(mimeTypes={ "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" })
     */
    private $excel;

//////////////////////////////////////////////////

    /**
     * @return mixed
     */
    public function getExcel()
    {
        return $this->excel;
    }

    /**
     * @param mixed $excel
     */
    public function setExcel($excel): void
    {
        $this->excel = $excel;
    }


}