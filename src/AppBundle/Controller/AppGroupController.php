<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
                'AppGroup/groupList.html.twig', array(
                'app_groups' => $appGroups,
            ));
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
            $em=$this->getDoctrine()->getManager();
            $appGroup = $em->getRepository('AppBundle:AppGroup')->find($slug);

            return $this->render(
                'AppGroup/group.html.twig',
                array('app_group' => $appGroup )
            );
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
            $em=$this->getDoctrine()->getManager();
            $appUser = $em->getRepository('AppBundle:AppUser')->find($slug);

            $appGroups = $appUser->getAppGroups();

            return $this->render(
                'AppGroup/usersGroupList.html.twig',
                array('app_groups' => $appGroups )
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }
}