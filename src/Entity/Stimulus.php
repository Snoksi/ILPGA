<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StimulusRepository")
 */
class Stimulus
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $playCount;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $speakerLang;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $speakerGender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $speakerAge;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="stimuli")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $page;



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
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age): void
    {
        $this->age = $age;
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
    public function getSpeakerLang()
    {
        return $this->speakerLang;
    }

    /**
     * @param mixed $speakerLang
     */
    public function setSpeakerLang($speakerLang): void
    {
        $this->speakerLang = $speakerLang;
    }

    /**
     * @return mixed
     */
    public function getSpeakerGender()
    {
        return $this->speakerGender;
    }

    /**
     * @param mixed $speakerGender
     */
    public function setSpeakerGender($speakerGender): void
    {
        $this->speakerGender = $speakerGender;
    }

    /**
     * @return mixed
     */
    public function getSpeakerAge()
    {
        return $this->speakerAge;
    }

    /**
     * @param mixed $speakerAge
     */
    public function setSpeakerAge($speakerAge): void
    {
        $this->speakerAge = $speakerAge;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     */
    public function setPage($page): void
    {
        $this->page = $page;
    }

    /**
     * @return mixed
     */
    public function getPlayCount()
    {
        return $this->playCount;
    }

    /**
     * @param mixed $playCount
     */
    public function setPlayCount($playCount): void
    {
        $this->playCount = $playCount;
    }

}
