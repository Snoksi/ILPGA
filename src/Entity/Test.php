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
     * @ORM\Column(type="string", length=75)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $random;

    /**
     * @ORM\Column(type="boolean")
     */
    private $notified;

    /**
     * @ORM\Column(type="string")
     */
    private $stimulusTest;

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

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="test", cascade={"persist"})
     */
    private $pages;



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

    /**
     * @return mixed
     */
    public function getStimulusTest()
    {
        return $this->stimulusTest;
    }

    /**
     * @param mixed $stimulusTest
     */
    public function setStimulusTest($stimulusTest): void
    {
        $this->stimulusTest = $stimulusTest;
    }

    /**
     * @return mixed
     */
    public function getRandom()
    {
        return $this->random;
    }

    /**
     * @param mixed $random
     */
    public function setRandom($random): void
    {
        $this->random = $random;
    }

    /**
     * @param mixed $random
     */
    public function isRandom()
    {
        return $this->random;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages): void
    {
        $this->pages = $pages;
    }

    public function addPage(Page $page)
    {
        $this->pages[] = $page;
    }
}
