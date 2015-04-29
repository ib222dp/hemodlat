<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="statusupdate")
 */
class StatusUpdate
{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="statusUpdates")
     * @ORM\JoinColumn(name="app_user_id", referencedColumnName="id")
     */
    private $appUser;

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

    public function getAppUser()
    {
        return $this->appUser;
    }

    public function setAppUser($appUser)
    {
        $this->appUser = $appUser;
    }

}

