<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Comment;
use \DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlankConstraint;

class CommentController extends Controller
{
    public function createAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $post = Request::createFromGlobals();

            if ($post->request->has('submit'))
            {
                $comment = new Comment();

                $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

                $comment->setAppUser($appUser);

                $message = $post->request->get('message');

                $notBlankConstraint = new NotBlankConstraint();
                $notBlankConstraint->message = 'Your customized error message';

                $errors = $this->get('validator')->validate(
                    $message,
                    $notBlankConstraint
                );

                $comment->setMessage($message);

                $comment->setCreationDate(new DateTime());

                $comment->setStatusUpdate($this->getDoctrine()->getRepository('AppBundle:StatusUpdate')->find($slug));

                $em = $this->getDoctrine()->getManager();
                $em->persist($comment);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }

    }
}