<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="name", message="Grödan finns redan")
 * @ORM\Table(name="crop")
 */
class Crop
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
     * @ORM\ManyToMany(targetEntity="AppUser", mappedBy="crops")
     * @ORM\JoinTable(name="appusers_crops",
     * joinColumns={@ORM\JoinColumn(name="app_user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="crop_id", referencedColumnName="id")}
     * )
     **/
    private $appUsers;

    public function __construct() {
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

    public function getAppUsers()
    {
        return $this->appUsers;
    }

    public function setAppUsers($appUsers)
    {
        $this->appUsers = $appUsers;
    }

}
