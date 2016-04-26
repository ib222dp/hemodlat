<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="pmthread")
 */
class PMThread
{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="createdPMThreads")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    protected $creator;

    /**
     * @ORM\ManyToMany(targetEntity="AppUser", mappedBy="PMThreads")
     * @ORM\JoinTable(name="appusers_pmthreads",
     * joinColumns={@ORM\JoinColumn(name="app_user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="pmthread_id", referencedColumnName="id")}
     * )
     **/
    protected $participants;

    /**
     * @ORM\OneToMany(targetEntity="PrivateMessage", mappedBy="PMThread")
     */
    protected $PMs;

    public function __construct() {
        $this->participants = new ArrayCollection();
        $this->PMs = new ArrayCollection();
    }

    public function getId ()
    {
        return $this->id;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    public function getParticipants()
    {
        return $this->participants;
    }

    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    public function getPMs()
    {
        return $this->PMs;
    }

    public function setPMs($PMs)
    {
        $this->PMs = $PMs;
    }

}