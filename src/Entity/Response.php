<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResponseRepository")
 */
class Response
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\OneToOne(targetEntity="Page")
     * @ORM\JoinColumn(name="name", referencedColumnName="content")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $response;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\OneToOne(targetEntity="Profil")
     * @ORM\JoinColumn(name="profil_id", referencedColumnName="id")
     */
    private $profil;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\OneToOne(targetEntity="Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    private $page;
//////////////////////////////////////////////
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
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response): void
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * @param mixed $profil
     */
    public function setProfil($profil): void
    {
        $this->profil = $profil;
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

}
