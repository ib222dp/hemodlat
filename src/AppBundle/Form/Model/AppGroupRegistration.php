<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\AppGroup;

class AppGroupRegistration
{
    /**
     * @Assert\Type(type="AppBundle\Entity\AppGroup")
     * @Assert\Valid()
     */
    protected $appGroup;


    public function setAppGroup(AppGroup $appGroup)
    {
        $this->appGroup = $appGroup;
    }

    public function getAppGroup()
    {
        return $this->appGroup;
    }

}