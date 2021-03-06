<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="location")
 */
class Location
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
     * @ORM\ManyToOne(targetEntity="County", inversedBy="locations")
     * @ORM\JoinColumn(name="county_id", referencedColumnName="id")
     */
    protected $county;

    /**
     * @ORM\OneToMany(targetEntity="AppUser", mappedBy="location")
     */
    protected $appUsers;

    public function __construct()
    {
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

    public function getCounty()
    {
        return $this->county;
    }

    public function getAppUsers()
    {
        return $this->appUsers;
    }

}
