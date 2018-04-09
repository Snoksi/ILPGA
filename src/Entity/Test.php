<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
 */
class Test
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notified;

    /**
     * @ORM\OneToOne(targetEntity="Stimulus")
     * @JoinColumn(name="stimulus_test_id", referencedColumnName="id")
     */
    private $audioTest;

    /**
     * @ORM\OneToOne(targetEntity="Page", cascade={"persist"})
     * @JoinColumn(name="instruction_page_id", referencedColumnName="id")
     */
    private $instructionPage;

    /**
     * Many Tests have one Folder
     * @ORM\ManyToOne(targetEntity="TestFolder", inversedBy="tests")
     * @ORM\JoinColumn(name="folder_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $folder;



    public function getId()
    {
        return $this->id;
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
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * @param TestFolder $folder
     */
    public function setFolder(TestFolder $folder = null)
    {
        $this->folder = $folder;
    }

    /**
     * @return mixed
     */
    public function getNotified()
    {
        return $this->notified;
    }

    /**
     * @param mixed $notified
     */
    public function setNotified($notified): void
    {
        $this->notified = $notified;
    }

    /**
     * @return mixed
     */
    public function getAudioTest()
    {
        return $this->audioTest;
    }

    /**
     * @param mixed $audioTest
     */
    public function setAudioTest($audioTest): void
    {
        $this->audioTest = $audioTest;
    }

    /**
     * @return mixed
     */
    public function getInstructionPage()
    {
        return $this->instructionPage;
    }

    /**
     * @param mixed $instructionPage
     */
    public function setInstructionPage($instructionPage): void
    {
        $this->instructionPage = $instructionPage;
    }


    public function addInstructionPage($instructionPage): void
    {
        $this->setInstructionPage($instructionPage);
    }
}
