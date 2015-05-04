<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Comment;
use \DateTime;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    public function createAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $post = Request::createFromGlobals();

            if ($post->request->has('submit'))
            {
                $message = $post->request->get('message');

                if(isset($message) && (!empty($message)))
                {
                    $comment = new Comment();

                    $comment->setMessage($message);

                    $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());
                    $comment->setAppUser($appUser);

                    $comment->setCreationDate(new DateTime());

                    $comment->setStatusUpdate($this->getDoctrine()->getRepository('AppBundle:StatusUpdate')->find($slug));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($comment);
                    $em->flush();

                    return $this->redirectToRoute('homepage');
                }
                else
                {
                    return $this->render('StatusUpdate/index.html.twig');
                }
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}