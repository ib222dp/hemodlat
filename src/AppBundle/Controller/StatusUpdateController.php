<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \DateTime;
use AppBundle\Entity\AppUser;
use AppBundle\Entity\StatusUpdate;
use AppBundle\Form\Type\StatusUpdateType;

class StatusUpdateController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $friendships = $appUser->getFriendships();

            $updatesArray = array();

            foreach($friendships as $friendship)
            {
                if($friendship->getFriendshipType()->getFshipType() == "Accepted")
                {
                    $updates = $friendship->getFriendUser()->getStatusUpdates();
                    array_push($updatesArray, $updates);
                }
            }

            $newArray = array();

            foreach($updatesArray as $innerArray)
            {
               foreach($innerArray as $update2)
               {
                   array_push($newArray, $update2);
               }
            }

            $ownUpdates = $appUser->getStatusUpdates();

            foreach($ownUpdates as $ownUpdate)
            {
                array_push($newArray, $ownUpdate);
            }

            usort($newArray, function($a, $b)
            {
                return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
            });

            return $this->render(
                'StatusUpdate/index.html.twig',
                array('app_user' => $appUser, 'updates' => $newArray)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/statusupdate/create", name="status_update_create")
     */
    public function createStatusUpdateAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $statusUpdate = new StatusUpdate();

            $form = $this->createForm(
                new StatusUpdateType(),
                $statusUpdate,
                array('action' => $this->generateUrl('status_update_create'))
            );

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $statusUpdate = $form->getData();

                $statusUpdate->setAppUser($appUser);

                $statusUpdate->setCreationDate(new DateTime());

                $em->persist($statusUpdate);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }

            return $this->render(
                'StatusUpdate/register.html.twig',
                array('app_user' => $appUser, 'form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}
