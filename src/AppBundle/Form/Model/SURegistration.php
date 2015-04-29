<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use AppBundle\Entity\StatusUpdate;

class SURegistration
{
    /**
     * @Assert\Type(type="AppBundle\Entity\StatusUpdate")
     * @Assert\Valid()
     */
    protected $statusUpdate;


    public function setStatusUpdate(StatusUpdate $statusUpdate)
    {
        $this->statusUpdate = $statusUpdate;
    }

    public function getStatusUpdate()
    {
        return $this->statusUpdate;
    }

}