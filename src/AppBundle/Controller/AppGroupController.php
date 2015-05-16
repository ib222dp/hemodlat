<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \DateTime;
use AppBundle\Entity\AppUser;
use AppBundle\Entity\AppGroup;
use AppBundle\Form\Type\AppGroupType;

class AppGroupController extends Controller
{
    /**
     * @Route("/groups", name="groups")
     */
    public function showAppGroupsAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appGroups = $this->getDoctrine()->getRepository('AppBundle:AppGroup')->findAll();

            return $this->render(
                'AppGroup/groupList.html.twig',
                array('app_groups' => $appGroups)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/groups/{slug}", name="group_show")
     */
    public function showAppGroupAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appGroup = $this->getDoctrine()->getRepository('AppBundle:AppGroup')->find($slug);

            if($appGroup === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                return $this->render(
                    'AppGroup/group.html.twig',
                    array('app_group' => $appGroup)
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/users/{slug}/groups", name="users_groups")
     */
    public function showUsersGroupsAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

            if($appUser === null)
            {
                return $this->createNotFoundException();
            }
            else
            {
                $appGroups = $appUser->getAppGroups();

                return $this->render(
                    'AppGroup/usersGroupList.html.twig',
                    array('app_groups' => $appGroups)
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/group/create", name="group_create")
     */
    public function createAppGroupAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appGroup = new AppGroup();

            $form = $this->createForm(
                new AppGroupType(),
                $appGroup,
                array('action' => $this->generateUrl('group_create'))
            );

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();

                $appGroup = $form->getData();

                $creator = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());
                $appGroup->setCreator($creator);

                $appGroup->setCreationDate(new DateTime());

                $em->persist($appGroup);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }

            return $this->render(
                'AppGroup/register.html.twig',
                array('form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }
}