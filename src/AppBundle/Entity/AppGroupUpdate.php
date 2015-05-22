<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="appgroupupdate")
 */
class AppGroupUpdate
{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=1000)
     * @Assert\NotBlank()
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="AppGroup", inversedBy="appGroupUpdates")
     * @ORM\JoinColumn(name="app_group_id", referencedColumnName="id")
     */
    protected $appGroup;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="appGroupUpdates")
     * @ORM\JoinColumn(name="app_user_id", referencedColumnName="id")
     */
    protected $appUser;

    /**
     * @ORM\Column(name="creationdate", type="datetime")
     */
    protected $creationDate;


    public function getId ()
    {
        return $this->id;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getAppGroup()
    {
        return $this->appGroup;
    }

    public function setAppGroup($appGroup)
    {
        $this->appGroup = $appGroup;
    }

    public function getAppUser()
    {
        return $this->appUser;
    }

    public function setAppUser($appUser)
    {
        $this->appUser = $appUser;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

}

