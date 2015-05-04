<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Friendship;
use AppBundle\Entity\FriendshipType;

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
            $loggedInUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            if($appUser == $loggedInUser)
            {
                $updates = $loggedInUser->getStatusUpdates()->toArray();

                usort($updates, function($a, $b) {
                    return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
                });

                return $this->render(
                    'AppUser/profile.html.twig', array(
                    'app_user' => $loggedInUser, 'updates' => $updates
                ));
            }
            else
            {
                if($loggedInUser->getFriendships()->isEmpty())
                {
                    return $this->render(
                        'AppUser/user.html.twig',
                        array(
                            'app_user' => $appUser
                        )
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
                                    array(
                                        'app_user' => $appUser
                                    )
                                );
                            }
                            elseif ($friendship->getFriendshipType()->getFshipType() == "Accepted")
                            {

                                $friendUpdates = $appUser->getStatusUpdates()->toArray();

                                usort($friendUpdates, function($a, $b) {
                                    return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
                                });

                                return $this->render(
                                    'AppUser/friend.html.twig',
                                    array(
                                        'app_user' => $appUser, 'updates' => $friendUpdates
                                    )
                                );
                            }
                            elseif ($friendship->getFriendshipType()->getFshipType() == "Asked")
                            {
                                return $this->render(
                                    'AppUser/askedUser.html.twig',
                                    array(
                                        'app_user' => $appUser
                                    )
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
                        array(
                            'app_user' => $appUser
                        )
                    );
                }
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}