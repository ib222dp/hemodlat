<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \DateTime;
use AppBundle\Entity\PMReception;
use AppBundle\Entity\PMThreadParticipation;
use AppBundle\Entity\PrivateMessage;
use AppBundle\Form\Type\PMType;

class PMController extends Controller
{
    /**
     * @Route("pmthreads/{slug}/pm/create", name="pm_create")
     */
    public function createAction(Request $request, $slug)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();
            $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());
            $friendArray = array();

            foreach ($appUser->getFriendships() as $friendship)
            {
                if ($friendship->getFriendshipType()->getFshipType() == "Accepted")
                {
                    array_push($friendArray, $friendship->getFriendUser());
                }
            }

            $form = $this->createForm(
                new PMType($friendArray),
                array('action' => $this->generateUrl('pm_create', array('slug' => $slug)))
            );

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $message = $form['message']->getData();
                $recipients = $form['recipients']->getData();

                $PM = new PrivateMessage();
                $PM->setMessage($message);
                $PM->setCreationDate(new DateTime());
                $PMThread = $em->getRepository('AppBundle:PMThread')->find($slug);
                $PM->setPMThread($PMThread);
                $PM->setCreator($appUser);

                foreach($recipients as $recipient)
                {
                    $PMReception = new PMReception();
                    $PMReception->setAppUser($recipient);
                    $PMReception->setPM($PM);
                    $em->persist($PMReception);
                }

                array_unshift($recipients, $appUser);
                foreach($recipients as $recipient)
                {
                    $participation = $em->getRepository('AppBundle:PMThreadParticipation')
                        ->findOneBy(array('appUser' => $recipient, 'PMThread' => $PMThread));
                    if($participation == null)
                    {
                        $newParticipation = new PMThreadParticipation();
                        $newParticipation->setAppUser($recipient);
                        $newParticipation->setPMThread($PMThread);
                        $newParticipation->setParticipationType($em->getRepository('AppBundle:PMThreadParticipationType')->find(1));
                        $em->persist($newParticipation);
                    }
                    else
                    {
                        $participation->setParticipationType($em->getRepository('AppBundle:PMThreadParticipationType')->find(1));
                        $em->persist($participation);
                    }
                }

                $em->persist($PM);
                $em->flush();

                return $this->redirect($this->generateUrl('pmthread_show', array('slug' => $slug)));
            }

            return $this->render(
                'PM/register.html.twig',
                array('form' => $form->createView())
            );

        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}