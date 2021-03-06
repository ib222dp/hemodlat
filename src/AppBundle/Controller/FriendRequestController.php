<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AppUser;
use AppBundle\Entity\Friendship;
use AppBundle\Entity\FriendshipType;

class FriendRequestController extends Controller
{
    /**
     * @Route("/friendrequests", name="friendrequests")
     */
    public function showFriendRequestsAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

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
                'FriendRequest/requestList.html.twig',
                array('resource' => $appUser, 'list' => $requestArray)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/sentfriendrequests", name="sentfriendrequests")
     */
    public function showSentFriendRequestsAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $friendships = $appUser->getFriendships();

            $requestArray = array();

            foreach($friendships as $friendship)
            {
                $fType = $friendship->getFriendshipType()->getFshipType();
                if ($fType == "Asked")
                {
                    array_push($requestArray, $friendship->getFriendUser());
                }
            }

            return $this->render(
                'FriendRequest/sentRequestList.html.twig',
                array('resource' => $appUser, 'list' => $requestArray)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("users/{slug}/createrequest", name="requestform")
     */
    public function createFriendRequestAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $post = Request::createFromGlobals();

            if ($post->request->has('submit'))
            {
                $friendship = new Friendship();

                $friendship->setAppUser($this->getUser());

                $friendship->setFriendUser($this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug));

                $friendship->setFriendshipType($this->getDoctrine()->getRepository('AppBundle:FriendshipType')->find(3));

                $friendship2 = new Friendship();

                $friendship2->setAppUser($appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug));

                $friendship2->setFriendUser($this->getUser());

                $friendship2->setFriendshipType($this->getDoctrine()->getRepository('AppBundle:FriendshipType')->find(1));

                $em = $this->getDoctrine()->getManager();

                $em->persist($friendship);
                $em->persist($friendship2);
                $em->flush();

                return $this->redirect($this->generateUrl('user_show', array('slug' => $slug )));
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("users/{slug}/acceptrequest", name="acceptrequestform")
     */
    public function acceptFriendRequestAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $post = Request::createFromGlobals();

            if ($post->request->has('submit'))
            {
                $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

                $friendUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

                $friendships = $appUser->getFriendships();

                $em = $this->getDoctrine()->getManager();

               foreach($friendships as $friendship)
               {
                   if($friendship->getFriendUser() == $friendUser)
                   {
                       $friendshipType = $this->getDoctrine()->getRepository('AppBundle:FriendshipType')->findOneByFshipType("Accepted");

                       $friendship->setFriendshipType($friendshipType);

                       $em->persist($friendship);
                   }
               }

                $friendships2 = $friendUser->getFriendships();

                foreach($friendships2 as $friendship2)
                {
                    if($friendship2->getFriendUser() == $appUser)
                    {
                        $friendshipType = $this->getDoctrine()->getRepository('AppBundle:FriendshipType')->findOneByFshipType("Accepted");

                        $friendship2->setFriendshipType($friendshipType);

                        $em->persist($friendship2);
                    }
                }

                $em->flush();

                return $this->redirect($this->generateUrl('user_show', array('slug' => $slug )));
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}

