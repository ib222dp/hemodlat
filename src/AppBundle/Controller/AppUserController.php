<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Friendship;
use AppBundle\Form\Model\FshipRegistration;
use AppBundle\Form\Type\FshipType;
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

            $forms = array();

            foreach($appUsers as $appUser)
            {
                $fshipRegistration = new FshipRegistration();
                $forms[$appUser->getId()] = $this ->createForm(new FshipType(), $fshipRegistration, array(
                    'action' => $this->generateUrl('fship_create'), ));
            }

            return $this->render(
                'AppUser/userList.html.twig', array(
                'app_users' => $appUsers, 'forms' => $forms
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

            return $this->render(
                'AppUser/user.html.twig', array(
                'app_user' => $appUser,
            ));
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}