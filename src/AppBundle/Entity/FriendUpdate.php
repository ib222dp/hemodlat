<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendupdate")
 */
class FriendUpdate
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
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="receivedFriendUpdates")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    protected $receiver;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="createdFriendUpdates")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    protected $creator;

    /**
     * @ORM\Column(name="creationdate", type="datetime")
     */
    protected $creationDate;

    /**
     * @ORM\OneToMany(targetEntity="FriendUpdateComment", mappedBy="friendUpdate")
     */
    protected $comments;

    public function __construct() {
        $this->comments = new ArrayCollection();
    }

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

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments($comments)
    {
        $this->comments = $comments;
    }

}

