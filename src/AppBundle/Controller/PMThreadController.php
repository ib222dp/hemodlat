<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \DateTime;
use AppBundle\Entity\PrivateMessage;
use AppBundle\Entity\PMReception;
use AppBundle\Entity\PMThreadParticipation;
use AppBundle\Entity\PMThread;
use AppBundle\Form\Type\PMThreadType;

class PMThreadController extends Controller
{
    /**
     * @Route("/pmthreads", name="pmthreads")
     */
    public function showPMThreadsAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $loggedInUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());
            $participations = $loggedInUser->getPMThreadParticipations()->toArray();

            $usersPMs = array();

            foreach ($participations as $participation)
            {
                $pType = $participation->getParticipationType();
                $thread = $participation->getPMThread();
                $PMs = $thread->getPMs()->toArray();
                usort($PMs, function($a, $b)
                {
                    return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
                });

                if($pType->getParticipationType() == 'Active')
                {
                    foreach ($PMs as $PM)
                    {
                        array_push($usersPMs, $PM);
                        break;
                    }
                }
                else
                {
                    foreach ($PMs as $PM)
                    {
                        $receptions = $PM->getPMReceptions()->toArray();
                        $recipients = array();
                        foreach($receptions as $reception)
                        {
                            array_push($recipients, $reception->getAppUser());
                        }
                        if($PM->getCreator() == $loggedInUser || in_array($loggedInUser, $recipients))
                        {
                            array_push($usersPMs, $PM);
                            break;
                        }
                    }
                }
            }

            usort($usersPMs, function($a, $b)
            {
                return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
            });

            return $this->render(
                'PMThread/PMThreadList.html.twig',
                array('PMs' => $usersPMs)
            );

        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/pmthreads/{slug}", name="pmthread_show")
     */
    public function showPMThreadAction($slug)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();
            $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());
            $PMThread = $em->getRepository('AppBundle:PMThread')->find($slug);

            if($PMThread === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                $PMThreadParticipation = $em->getRepository('AppBundle:PMThreadParticipation')
                    ->findOneBy(array('appUser' => $appUser, 'PMThread' => $PMThread));

                $PMs = $PMThread->getPMs()->toArray();
                usort($PMs, function($a, $b)
                {
                    return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
                });

                if($PMThreadParticipation->getParticipationType()->getParticipationType() == 'Active')
                {
                    return $this->render(
                        'PMThread/PMThread.html.twig',
                        array('PMs' => $PMs)
                    );
                }
                else
                {
                    $usersPMs = array();
                    $newestPMFound = false;
                    foreach ($PMs as $PM)
                    {
                        if($newestPMFound)
                        {
                            array_push($usersPMs, $PM);
                        }
                        else
                        {
                            $receptions = $PM->getPMReceptions()->toArray();
                            $recipients = array();
                            foreach($receptions as $reception)
                            {
                                array_push($recipients, $reception->getAppUser());
                            }
                            if ($PM->getCreator() == $appUser || in_array($appUser, $recipients)) {
                                array_push($usersPMs, $PM);
                                $newestPMFound = true;
                            }
                        }
                    }

                    return $this->render(
                        'PMThread/PMThread.html.twig',
                        array('PMs' => $usersPMs)
                    );
                }
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/pmthreads/create", name="pmthread_create")
     */
    public function createAction(Request $request)
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
                new PMThreadType($friendArray),
                array('action' => $this->generateUrl('pmthread_create'))
            );

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $subject = $form['subject']->getData();
                $message = $form['message']->getData();
                $recipients = $form['recipients']->getData();

                $PMThread = new PMThread();
                $PMThread->setCreator($appUser);
                $PMThread->setSubject($subject);

                $PM = new PrivateMessage();
                $PM->setMessage($message);
                $PM->setCreationDate(new DateTime());
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
                    $participation = new PMThreadParticipation();
                    $participation->setAppUser($recipient);
                    $participation->setPMThread($PMThread);
                    $participation->setParticipationType($em->getRepository('AppBundle:PMThreadParticipationType')->find(1));
                    $em->persist($participation);
                }

                $em->persist($PMThread);
                $em->persist($PM);
                $em->flush();

                return $this->redirectToRoute('pmthreads');
            }

            return $this->render(
                'PMThread/register.html.twig',
                array('form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}