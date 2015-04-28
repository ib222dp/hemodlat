<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
                'AppUser/userList.html.twig', array(
                'app_users' => $appUsers
            ));
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/users/{slug}", name="user_show")
     */
    public function showUserAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);
            $loggedInUser = $this->getUser();
            return $this->render(
                'AppUser/user.html.twig', array(
                'app_user' => $appUser, 'loggedin_user' => $loggedInUser
            ));
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}