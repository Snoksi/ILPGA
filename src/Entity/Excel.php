<?php

// src/Entity/Excel.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExcelRepository")
 */
class Excel
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\NotBlank(message="Please, upload the file as a xlsx file.")
     * @Assert\File(mimeTypes={ "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" })
     */
    private $excel;

    /**
     * @ORM\Column(type="string", length=255), nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255), nullable=true)
     */
    private $source;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @ORM\OneToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ORM\OneToOne(targetEntity="App\Entity\Questionnaire")
     * @ORM\JoinColumn(name="questionnaire_name", referencedColumnName="name")
     */
    private $test;

//////////////////////////////////////////////////

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source): void
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param mixed $questionnaire
     */
    public function setTest($test): void
    {
        $this->test = $test;
    }

}