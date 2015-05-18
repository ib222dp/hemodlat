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
                'AppUser/userList.html.twig',
                array('list' => $appUsers)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("users/{slug}", name="user_show")
     */
    public function showUserAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

            if($appUser === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                $loggedInUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

                if ($appUser == $loggedInUser)
                {
                    return $this->redirectToRoute('profile_show');
                }
                else
                {
                    if ($loggedInUser->getFriendships()->isEmpty())
                    {
                        return $this->render(
                            'AppUser/user.html.twig',
                            array('app_user' => $appUser)
                        );
                    }
                    else
                    {
                        foreach ($loggedInUser->getFriendships() as $friendship)
                        {
                            if ($friendship->getFriendUser() == $appUser)
                            {
                                if ($friendship->getFriendshipType()->getFshipType() == "Pending")
                                {
                                    return $this->render(
                                        'AppUser/pendingUser.html.twig',
                                        array('app_user' => $appUser)
                                    );
                                }
                                elseif ($friendship->getFriendshipType()->getFshipType() == "Accepted")
                                {
                                    return $this->redirect($this->generateUrl('friend_show', array('slug' => $slug)));
                                }
                                elseif ($friendship->getFriendshipType()->getFshipType() == "Asked")
                                {
                                    return $this->render(
                                        'AppUser/askedUser.html.twig',
                                        array('app_user' => $appUser)
                                    );
                                }
                            }
                            else
                            {
                                continue;
                            }
                        }

                        return $this->render(
                            'AppUser/user.html.twig',
                            array('app_user' => $appUser)
                        );
                    }
                }
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}