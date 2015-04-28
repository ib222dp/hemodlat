<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FriendRequestController extends Controller
{
    /**
     * @Route("users/{slug}/friendrequests", name="friendrequests")
     */
    public function showFriendRequestsAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

            $friendships = $appUser->getFriendships();

            $requestArray = array();

            foreach($friendships as $friendship)
            {
                $fType = $friendship->getFriendshipType()->getFshipType();
                if ($fType == "Pending")
                {
                    array_push($requestArray, $friendship->getFriendUser());
                }
            }

            return $this->render(
                'FriendRequest/requestList.html.twig', array(
                'requests' => $requestArray,
            ));
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    public function createFriendRequestAction()
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