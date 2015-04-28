<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Friendship;
use AppBundle\Entity\FriendshipType;

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

            $friendships = $appUser->getFriendships();

            $friendArray = array();

            foreach($friendships as $friendship)
            {
                $fType = $friendship->getFriendshipType()->getFshipType();
                if ($fType == "Accepted")
                {
                    array_push($friendArray, $friendship->getFriendUser());
                }
            }

            return $this->render(
                'Friend/friendList.html.twig', array(
                'friends' => $friendArray,
            ));
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
                $appUserID = $this->getUser()->getId();

                $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($appUserID);

                $friendUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

                $friendships = $appUser->getFriendships();

                $em = $this->getDoctrine()->getManager();

                foreach($friendships as $friendship)
                {
                    if($friendship->getFriendUser() == $friendUser)
                    {
                        $em->remove($friendship);
                    }
                }

                $friendships2 = $friendUser->getFriendships();

                foreach($friendships2 as $friendship2)
                {
                    if($friendship2->getFriendUser() == $appUser)
                    {
                        $em->remove($friendship2);
                    }
                }

                $em->flush();
                return $this->redirectToRoute('users');
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}

