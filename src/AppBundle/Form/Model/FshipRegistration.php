<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\Friendship;

class FshipRegistration
{
    /**
     * @Assert\Type(type="AppBundle\Entity\Friendship")
     * @Assert\Valid()
     */
    protected $friendship;


    public function setFriendship(Friendship $friendship)
    {
        $this->friendship = $friendship;
    }

    public function getFriendship()
    {
        return $this->friendship;
    }

}