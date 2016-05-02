<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\PMReception;
use AppBundle\Entity\PMThreadParticipation;
use AppBundle\Entity\PrivateMessage;
use AppBundle\Form\Type\PMType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PMController extends Controller
{
    /**
     * @Route("pmthreads/{pmthread}/pms/{pm}/reply", name="pm_reply")
     */
    public function replyAction(Request $request, $pmthread, $pm)
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

            $oldPM = $em->getRepository('AppBundle:PrivateMessage')->find($pm);

            $oldReceptions = $oldPM->getPMReceptions()->toArray();

            $oldRecipients = array();
            $oldRecipientsCopy = array();

            foreach($oldReceptions as $oldReception)
            {
                if($oldReception->getAppUser() !== $appUser) {
                    array_push($oldRecipients, $oldReception->getAppUser());
                    array_push($oldRecipientsCopy, $oldReception->getAppUser());
                }
            }

            foreach($friendArray as $friend)
            {
                if(!in_array($friend, $oldRecipientsCopy))
                {
                    array_push($oldRecipientsCopy, $friend);
                }
            }

            $form = $this->createForm(
                new PMType($oldRecipientsCopy, $oldRecipients),
                array('action' => $this->generateUrl('pm_reply', array('pmthread' => $pmthread, 'pm' => $pm)))
            );

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $message = $form['message']->getData();
                $recipients = $form['recipients']->getData();

                $newPM = new PrivateMessage();
                $newPM->setMessage($message);
                $newPM->setCreationDate(new DateTime());
                $PMThread = $em->getRepository('AppBundle:PMThread')->find($pmthread);
                $newPM->setPMThread($PMThread);
                $newPM->setCreator($appUser);

                foreach($recipients as $recipient)
                {
                    $PMReception = new PMReception();
                    $PMReception->setAppUser($recipient);
                    $PMReception->setPM($newPM);
                    $em->persist($PMReception);
                }

                foreach($oldRecipients as $oldRec)
                {
                    if(!in_array($oldRec, $recipients))
                    {
                        $oldParticipation = $em->getRepository('AppBundle:PMThreadParticipation')
                            ->findOneBy(array('appUser' => $oldRec, 'PMThread' => $PMThread));
                        if($oldParticipation !== null)
                        {
                            $oldParticipation->setParticipationType($em->getRepository('AppBundle:PMThreadParticipationType')->find(2));
                            $em->persist($oldParticipation);
                        }
                    }
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

                $em->persist($newPM);
                $em->flush();

                return $this->redirect($this->generateUrl('pmthread_show', array('slug' => $pmthread)));
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