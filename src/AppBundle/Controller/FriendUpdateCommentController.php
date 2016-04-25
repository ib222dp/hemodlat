<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;
use AppBundle\Entity\FriendUpdateComment;

class FriendUpdateCommentController extends Controller
{
    public function createAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $post = Request::createFromGlobals();

            if ($post->request->has('submit'))
            {
                $lastRoute = $this->get('session')->get('last_route');
                $lastRouteName = $lastRoute['name'];
                if ($lastRouteName === 'friend_show') {
                    $lastRouteParams = $lastRoute['params'];
                    $lastRouteSlug = $lastRouteParams['slug'];
                    $lastRouteArray = array('slug' => $lastRouteSlug);
                } else {
                    $lastRouteArray = null;
                }

                $message = $post->request->get('message');

                if(isset($message) && (!empty($message)))
                {
                    $em = $this->getDoctrine()->getManager();

                    $friendUpdate = $em->getRepository('AppBundle:FriendUpdate')->find($slug);

                    if($friendUpdate === null)
                    {
                        return $this->redirect($this->generateUrl($lastRouteName, $lastRouteArray));
                    }
                    else
                    {
                        $comment = new FriendUpdateComment();

                        $comment->setMessage($message);

                        $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());
                        $comment->setCreator($appUser);

                        $comment->setCreationDate(new DateTime());

                        $comment->setFriendUpdate($friendUpdate);

                        $em->persist($comment);
                        $em->flush();

                        return $this->redirect($this->generateUrl($lastRouteName, $lastRouteArray));
                    }
                }
                else
                {
                    return $this->redirect($this->generateUrl($lastRouteName, $lastRouteArray));
                }
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}