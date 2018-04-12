<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page
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
<<<<<<< HEAD
    private $type = "page";

    /**
     * @ORM\Column(type="string", nullable=true)
=======
    private $title;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $type = "page";

    /**
     * @ORM\Column(type="text", nullable=true)
>>>>>>> c5d006e04aa4aafbea09d69efe123635baf2240a
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $position;

    /**
     * One Page belongs to one test
     * @ORM\ManyToOne(targetEntity="Test")
<<<<<<< HEAD
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id")
     */
    private $test;

=======
     * @ORM\JoinColumn(name="test_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $test;

    /**
     * One Page belongs to many Stimulus
     * @ORM\OneToMany(targetEntity="Stimulus", mappedBy="page", cascade={"persist"})
     */
    private $stimuli;


    /**
     * Page constructor.
     */
    public function __construct()
    {
        $this->stimuli = [];
    }

>>>>>>> c5d006e04aa4aafbea09d69efe123635baf2240a

    public function getId()
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getContent(): ?array
    {
        return $this->content;
    }

    public function setContent($content): self
    {
        if(is_array($content)){
            $content = serialize($content);
        }
        $this->content = $content;
        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param mixed $test
     */
    public function setTest($test): void
    {
        $this->test = $test;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getStimuli()
    {
        return $this->stimuli;
    }

    /**
     * @param mixed $stimuli
     */
    public function setStimuli($stimuli): void
    {
        $this->stimuli = $stimuli;
    }

    /**
     * @param $stimulus
     */
    public function addStimulus($stimulus)
    {
        $this->stimuli[] = $stimulus;
    }

}
