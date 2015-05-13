<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AppUser;

class AppUserController extends Controller
{
     /**
     * @Route("/users", name="users")
     */
    public function showUsersAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUsers = $this->getDoctrine()->getRepository('AppBundle:AppUser')->findAll();

            return $this->render(
                'Friend/userList.html.twig',
                array('app_users' => $appUsers)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/profile", name="profile_show")
     */
    public function showProfileAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $loggedInUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $updates = $loggedInUser->getStatusUpdates()->toArray();

            usort($updates, function($a, $b)
            {
                return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
            });

            return $this->render(
                'AppUser/profile.html.twig',
                array('app_user' => $loggedInUser, 'updates' => $updates)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}