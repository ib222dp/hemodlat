<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="privatemessage")
 */
class PrivateMessage
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
     * @ORM\ManyToOne(targetEntity="PMThread", inversedBy="PMs")
     * @ORM\JoinColumn(name="pmthread_id", referencedColumnName="id")
     */
    protected $PMThread;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="createdPMs")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    protected $creator;

    /**
     * @ORM\ManyToMany(targetEntity="AppUser", mappedBy="receivedPMs")
     * @ORM\JoinTable(name="appusers_privatemessages",
     * joinColumns={@ORM\JoinColumn(name="app_user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="privatemessage_id", referencedColumnName="id")}
     * )
     **/
    protected $recipients;

    public function __construct() {
        $this->recipients = new ArrayCollection();
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

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
    }

    public function getPMThread()
    {
        return $this->PMThread;
    }

    public function setPMThread($PMThread)
    {
        $this->PMThread = $PMThread;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    public function getRecipients()
    {
        return $this->recipients;
    }

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }

}
