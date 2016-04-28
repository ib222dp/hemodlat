<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pmthreadparticipation")
 */
class PMThreadParticipation
{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="PMThreadParticipations")
     * @ORM\JoinColumn(name="app_user_id", referencedColumnName="id")
     */
    protected $appUser;

    /**
     * @ORM\ManyToOne(targetEntity="PMThread", inversedBy="participations")
     * @ORM\JoinColumn(name="pmthread_id", referencedColumnName="id")
     */
    protected $PMThread;

    /**
     * @ORM\ManyToOne(targetEntity="PMThreadParticipationType", inversedBy="participations")
     * @ORM\JoinColumn(name="participationtype_id", referencedColumnName="id")
     */
    protected $participationType;

    public function getAppUser()
    {
        return $this->appUser;
    }

    public function setAppUser($appUser)
    {
        $this->appUser = $appUser;
    }

    public function getPMThread()
    {
        return $this->PMThread;
    }

    public function setPMThread($PMThread)
    {
        $this->PMThread = $PMThread;
    }

    public function getParticipationType()
    {
        return $this->participationType;
    }

    public function setParticipationType($participationType)
    {
        $this->participationType = $participationType;
    }

}