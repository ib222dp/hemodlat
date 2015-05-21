<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Entity\AppGroupRepository")
 * @UniqueEntity(fields="name", message="Gruppen finns redan")
 * @ORM\Table(name="appgroup")
 */
class AppGroup
{
    /**
     * @ORM\Column(type="integer", length=11)
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
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="createdGroups")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    protected $creator;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     * @ORM\Column(name="creationdate", type="datetime")
     */
    protected $creationDate;

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

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
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
