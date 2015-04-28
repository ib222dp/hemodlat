<?php

namespace AppBundle\Controller;

use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

}

