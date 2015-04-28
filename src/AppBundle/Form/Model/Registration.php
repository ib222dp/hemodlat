<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\AppUser;

class Registration
{
    /**
     * @Assert\Type(type="AppBundle\Entity\AppUser")
     * @Assert\Valid()
     */
    protected $appUser;


    public function setAppUser(AppUser $appUser)
    {
        $this->appUser = $appUser;
    }

    public function getAppUser()
    {
        return $this->appUser;
    }

}