<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="friendship")
 */
class Friendship
{
    /**
     * @ORM\Column(type="integer", length=11)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="friendships")
     * @ORM\JoinColumn(name="app_user_id", referencedColumnName="id")
     */
    protected $appUser;

    /**
     * @ORM\ManyToOne(targetEntity="AppUser", inversedBy="friendships")
     * @ORM\JoinColumn(name="friend_user_id", referencedColumnName="id")
     */
    protected $friendUser;

    /**
     * @ORM\ManyToOne(targetEntity="FriendshipType", inversedBy="friendships")
     * @ORM\JoinColumn(name="friendshiptype_id", referencedColumnName="id")
     */
    protected $friendshipType;

    public function getAppUser()
    {
        return $this->appUser;
    }

    public function setAppUser($appUser)
    {
        $this->appUser = $appUser;
    }

    public function getFriendUser()
    {
        return $this->friendUser;
    }

    public function setFriendUser($friendUser)
    {
        $this->friendUser = $friendUser;
    }

    public function getFriendshipType()
    {
        return $this->friendshipType;
    }

    public function setFriendshipType($friendshipType)
    {
        $this->friendshipType = $friendshipType;
    }

}
