<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \DateTime;
use AppBundle\Entity\AppUser;
use AppBundle\Entity\FriendUpdate;
use AppBundle\Form\Type\FriendUpdateType;

class FriendUpdateController extends Controller
{
    /**
     * @Route("/friends/{slug}/updates/create", name="friend_update_create")
     */
    public function createFriendUpdateAction(Request $request, $slug)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $receiver = $em->getRepository('AppBundle:AppUser')->find($slug);

            if($receiver === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                $creator = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

                $friendUpdate = new FriendUpdate();

                $form = $this->createForm(
                    new FriendUpdateType(),
                    $friendUpdate,
                    array('action' => $this->generateUrl('friend_update_create', array('slug' => $slug)))
                );

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $friendUpdate = $form->getData();

                    $friendUpdate->setReceiver($receiver);

                    $friendUpdate->setCreator($creator);

                    $friendUpdate->setCreationDate(new DateTime());

                    $em->persist($friendUpdate);
                    $em->flush();

                    return $this->redirect($this->generateUrl('friend_show', array('slug' => $slug)));
                }

                return $this->render(
                    'FriendUpdate/register.html.twig',
                    array('resource' => $receiver, 'form' => $form->createView())
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}
