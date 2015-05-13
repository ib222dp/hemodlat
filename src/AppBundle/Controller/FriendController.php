<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AppUser;

class FriendController extends Controller
{
    /**
     * @Route("users/{slug}/friends", name="friends")
     */
    public function showFriendsAction($slug)
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
                $friendships = $appUser->getFriendships();

                $friendArray = array();

                foreach ($friendships as $friendship)
                {
                    $fType = $friendship->getFriendshipType()->getFshipType();
                    if ($fType == "Accepted")
                    {
                        array_push($friendArray, $friendship->getFriendUser());
                    }
                }

                return $this->render(
                    'Friend/friendList.html.twig',
                    array('friends' => $friendArray)
                );
            }
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
                            'Friend/user.html.twig',
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
                                        'Friend/pendingUser.html.twig',
                                        array('app_user' => $appUser)
                                    );
                                }
                                elseif ($friendship->getFriendshipType()->getFshipType() == "Accepted")
                                {
                                    $friendUpdates = $appUser->getStatusUpdates()->toArray();

                                    usort($friendUpdates, function ($a, $b)
                                    {
                                        return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
                                    });

                                    return $this->render(
                                        'Friend/friend.html.twig',
                                        array('app_user' => $appUser, 'updates' => $friendUpdates)
                                    );
                                }
                                elseif ($friendship->getFriendshipType()->getFshipType() == "Asked")
                                {
                                    return $this->render(
                                        'Friend/askedUser.html.twig',
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
                            'Friend/user.html.twig',
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

    /**
     * @Route("users/{slug}/deletefriend", name="deletefriendform")
     */
    public function deleteFriendAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $post = Request::createFromGlobals();

            if ($post->request->has('submit'))
            {
                $friendUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

                if($friendUser === null)
                {
                    throw $this->createNotFoundException();
                }
                else
                {
                    $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

                    $friendships = $appUser->getFriendships();

                    $em = $this->getDoctrine()->getManager();

                    foreach ($friendships as $friendship)
                    {
                        if ($friendship->getFriendUser() == $friendUser)
                        {
                            $em->remove($friendship);
                        }
                    }

                    $friendships2 = $friendUser->getFriendships();

                    foreach ($friendships2 as $friendship2)
                    {
                        if ($friendship2->getFriendUser() == $appUser)
                        {
                            $em->remove($friendship2);
                        }
                    }

                    $em->flush();

                    return $this->redirect($this->generateUrl('user_show', array('slug' => $slug)));
                }
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}

