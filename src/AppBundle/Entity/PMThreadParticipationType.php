<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="pmthreadparticipationtype")
 */
class PMThreadParticipationType
{
    /**
     * @ORM\Column(type="integer", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="participationtype", type="string", length=100)
     */
    protected $participationType;

    /**
     * @ORM\OneToMany(targetEntity="PMThreadParticipation", mappedBy="participationType")
     */
    protected $participations;

    public function __construct() {
        $this->participations = new ArrayCollection();
    }

    public function getParticipationType()
    {
        return $this->participationType;
    }

}
