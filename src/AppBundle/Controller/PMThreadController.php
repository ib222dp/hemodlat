<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PMThreadController extends Controller
{
    /**
     * @Route("/pmthreads", name="pmthreads")
     */
    public function showPMThreadsAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $loggedInUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());
            $createdPMThreads = $loggedInUser->getCreatedPMThreads()->toArray();
            $PMThreads = $loggedInUser->getPMThreads()->toArray();
            $threads = array_merge($createdPMThreads, $PMThreads);

            $newArray = array();

            foreach ($threads as $thread)
            {
                $PMs = $thread->getPMs();
                usort($PMs, function($a, $b)
                {
                    return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
                });
                foreach ($PMs as $PM) {
                    array_push($newArray, $PM);
                    break;
                }
            }

            return $this->render(
                'PMThread/PMThreadList.html.twig',
                array('PMs' => $newArray)
            );

        } else {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/pmthreads/create", name="pmthread_create")
     */
    public function createAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {

        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}