<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StatusUpdate;
use AppBundle\Form\Model\SURegistration;
use AppBundle\Form\Type\StatusUpdateType;
use AppBundle\Form\Type\SURegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AppUser;
use \DateTime;

use Symfony\Component\HttpFoundation\Request;

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
                $updates = $friendship->getFriendUser()->getStatusUpdates();

                array_push($updatesArray, $updates);
            }

            $newArray = array();

            foreach($updatesArray as $innerArray)
            {
               foreach($innerArray as $update2)
               {
                   array_push($newArray, $update2);
               }
            }

            usort($newArray, function($a, $b) {
                return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
            });

            return $this->render(
                'StatusUpdate/index.html.twig', array(
                'app_user' => $appUser, 'updatesArray' => $newArray
            ));
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/statusupdate/register", name="status_update_register")
     */
    public function registerStatusUpdateAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $suRegistration = new SURegistration();
            $form = $this->createForm(new SURegistrationType(), $suRegistration , array(
                'action' => $this->generateUrl('status_update_create'),
            ));

            return $this->render(
                'StatusUpdate/register.html.twig',
                array('form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    public function createStatusUpdateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new SURegistrationType(), new SURegistration());

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $suRegistration = $form->getData();

            $statusUpdate = $suRegistration->getStatusUpdate();

            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $statusUpdate->setAppUser($appUser);

            $statusUpdate->setCreationDate(new DateTime());

            $em->persist($statusUpdate);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'StatusUpdate/register.html.twig',
            array('form' => $form->createView())
        );
    }


}
