<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
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
            $em = $this->getDoctrine()->getManager();
            $appUser = $em->getRepository('AppBundle:AppUser')->find($slug);

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
                    array('resource' => $appUser, 'list' => $friendArray)
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/friends/{slug}", name="friend_show")
     */
    public function showFriendAction($slug)
    {
        $friend = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

        $friendUpdates = $friend->getStatusUpdates()->toArray();

        $receivedUpdates = $friend->getReceivedFriendUpdates()->toArray();

        $createdUpdates = $friend->getCreatedFriendUpdates()->toArray();

        $allUpdates = array_merge($friendUpdates, $receivedUpdates, $createdUpdates);

        usort($allUpdates, function ($a, $b)
        {
            return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
        });

        return $this->render(
            'Friend/friend.html.twig',
            array('resource' => $friend, 'updates' => $allUpdates)
        );

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

