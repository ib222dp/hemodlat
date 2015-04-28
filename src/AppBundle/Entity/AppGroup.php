<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="name", message="Gruppen finns redan")
 * @ORM\Table(name="appgroup")
 */
class AppGroup
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
     * @ORM\ManyToMany(targetEntity="AppUser", mappedBy="appGroups")
     * @ORM\JoinTable(name="appusers_appgroups",
     * joinColumns={@ORM\JoinColumn(name="app_user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="app_group_id", referencedColumnName="id")}
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
