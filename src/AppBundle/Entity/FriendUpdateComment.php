<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendupdatecomment")
 */
class FriendUpdateComment
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
     * @ORM\ManyToOne(targetEntity="FriendUpdate", inversedBy="comments")
     * @ORM\JoinColumn(name="friendupdate_id", referencedColumnName="id")
     */
    protected $friendUpdate;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="friendComments")
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

    public function getFriendUpdate()
    {
        return $this->friendUpdate;
    }

    public function setFriendUpdate($friendUpdate)
    {
        $this->friendUpdate = $friendUpdate;
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
