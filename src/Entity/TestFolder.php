<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestFolderRepository")
 */
class TestFolder
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
     * One TestFolder has many Tests
     * @ORM\OneToMany(targetEntity="Test", mappedBy="folder", cascade={"remove"})
     */
    private $tests;

    /**
     * Many Folders have one Parent
     * @ORM\ManyToOne(targetEntity="TestFolder", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * One TestFolder has many Children
     * @ORM\OneToMany(targetEntity="TestFolder", mappedBy="parent", cascade={"remove"})
     */
    private $children;


    /**
     * TestFolder constructor.
     */
    public function __construct()
    {
        $this->tests = [];
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TestFolder
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * @param ArrayCollection $tests
     */
    public function setTests(array $tests)
    {
        $this->tests = $tests;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param TestFolder $parent
     */
    public function setParent(TestFolder $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children): void
    {
        $this->children = $children;
    }
}
