<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendshiptype")
 */
class FriendshipType
{
    /**
     * @ORM\Column(type="integer", length=20)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="fshiptype", type="string", length=100)
     */
    protected $fshipType;

    /**
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="friendshipType")
     */
    protected $friendships;

    public function __construct() {
        $this->friendships = new ArrayCollection();
    }

    public function getFshipType()
    {
        return $this->fshipType;
    }

}
