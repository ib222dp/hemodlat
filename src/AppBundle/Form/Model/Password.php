<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class Password
{
    /**
     * @SecurityAssert\UserPassword(message = "Fel värde för nuvarande lösenord")
     */
    protected $oldPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     */
    protected $newPassword;


    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

}