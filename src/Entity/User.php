<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\Length(
     *     min = 3,
     *     max = 24,
     *     minMessage = "Votre nom d'utilisateur doit faire plus de {{ limit }} caractères.",
     *     maxMessage = "Votre nom d'utilisateur doit faire moins de {{ limit }} caractères.",
     * )
     * @Assert\NotBlank()
     */
    private $username;
    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;
    /**
     * @ORM\Column(type="string", length=75)
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=75, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=75)
     */
    private $lastname;
    /**
     * @ORM\Column(type="string", length=75)
     */
    private $firstname;

    /**
     * @ORM\Column(name="role", type="array")
     */
    private $roles;

    ///////////////////////////////////////////////////

    public function __construct()
    {
        $this->isActive = true;
    }
    /**
     * @Assert\IsTrue(message="Le mot de passe ne peut être égal à votre nom d'utilisateur")
     */
    public function isPasswordLegal()
    {
        return $this->getPlainPassword() !== $this->getUsername();
    }
    public function getSalt()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        $this->username = $username;
    }
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return string
     */
    public function getRole(){
        return current($this->getRoles());
    }

    /**
     * @param $role
     */
    public function setRole($role){
        $this->setRoles([$role]);
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
        ]);
    }
    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            $this->roles) = unserialize($serialized);
    }
}
