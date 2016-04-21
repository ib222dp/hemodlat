<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
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
     * @ORM\Column(name="creationdate", type="datetime")
     **/
    protected $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="StatusUpdate", inversedBy="comments")
     * @ORM\JoinColumn(name="statusupdate_id", referencedColumnName="id")
     */
    protected $statusUpdate;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="comments")
     * @ORM\JoinColumn(name="app_user_id", referencedColumnName="id")
     */
    protected $creator;

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

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getStatusUpdate()
    {
        return $this->statusUpdate;
    }

    public function setStatusUpdate($statusUpdate)
    {
        $this->statusUpdate = $statusUpdate;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

}
