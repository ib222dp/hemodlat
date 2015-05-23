<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \DateTime;
use AppBundle\Entity\AppUser;
use AppBundle\Entity\AppGroup;
use AppBundle\Entity\AppGroupUpdate;
use AppBundle\Form\Type\AppGroupUpdateType;

class AppGroupUpdateController extends Controller
{
    /**
     * @Route("/groups/{slug}/updates/create", name="group_update_create")
     */
    public function createAppGroupUpdateAction(Request $request, $slug)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $appGroup = $em->getRepository('AppBundle:AppGroup')->find($slug);

            if($appGroup === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

                $appGroupUpdate = new AppGroupUpdate();

                $form = $this->createForm(
                    new AppGroupUpdateType(),
                    $appGroupUpdate,
                    array('action' => $this->generateUrl('group_update_create', array('slug' => $slug)))
                );

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $appGroupUpdate = $form->getData();

                    $appGroupUpdate->setAppUser($appUser);

                    $appGroupUpdate->setAppGroup($appGroup);

                    $appGroupUpdate->setCreationDate(new DateTime());

                    $em->persist($appGroupUpdate);
                    $em->flush();

                    return $this->redirect($this->generateUrl('group_show', array('slug' => $slug)));
                }

                return $this->render(
                    'AppGroupUpdate/register.html.twig',
                    array('resource' => $appGroup, 'form' => $form->createView())
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}
