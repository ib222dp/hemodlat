<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="name", message="Platsen finns redan")
 * @ORM\Table(name="location")
 */
class Location
{
    /**
     * @ORM\Column(type="integer", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="AppUser", mappedBy="location")
     */
    protected $appUsers;

    public function __construct()
    {
        $this->appUsers = new ArrayCollection();
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getId ()
    {
        return $this->id;
    }

    public function getAppUsers ()
    {
        return $this->appUsers;
    }

}
