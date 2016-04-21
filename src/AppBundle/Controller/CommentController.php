<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;
use AppBundle\Entity\Comment;

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
                    $em = $this->getDoctrine()->getManager();

                    $statusUpdate = $em->getRepository('AppBundle:StatusUpdate')->find($slug);

                    if($statusUpdate === null)
                    {
                        return $this->render('StatusUpdate/index.html.twig');
                    }
                    else
                    {
                        $comment = new Comment();

                        $comment->setMessage($message);

                        $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());
                        $comment->setCreator($appUser);

                        $comment->setCreationDate(new DateTime());

                        $comment->setStatusUpdate($statusUpdate);

                        $em->persist($comment);
                        $em->flush();

                        return $this->redirectToRoute('homepage');
                    }
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