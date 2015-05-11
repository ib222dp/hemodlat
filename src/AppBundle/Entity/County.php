<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="county")
 */
class County
{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Location", mappedBy="county")
     */
    protected $locations;

    /**
     * @ORM\OneToMany(targetEntity="AppUser", mappedBy="county")
     */
    protected $appUsers;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->appUsers = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLocations()
    {
        return $this->locations;
    }

    public function getAppUsers()
    {
        return $this->appUsers;
    }
}
