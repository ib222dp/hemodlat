<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FriendshipController extends Controller
{
    /**
     * @Route("friendship/create", name="fship_create")
     */
    public function createAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {

        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}

