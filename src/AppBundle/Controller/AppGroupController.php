<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use \DateTime;
use AppBundle\Entity\AppUser;
use AppBundle\Entity\AppGroup;
use AppBundle\Form\Type\AppGroupType;
use AppBundle\Helper\Paginator;

class AppGroupController extends Controller
{
    /**
     * @Route("/groups", name="groups")
     */
    public function showAppGroupsAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $total_count = $em->getRepository('AppBundle:AppGroup')->getAppGroupsCount();

            $paginator = new Paginator();

            $pageArray = $paginator->getPagination($request, $total_count);

            $appGroups = $em->getRepository('AppBundle:AppGroup')->getPaginatedAppGroups($pageArray[0], $pageArray[1]);

            return $this->render(
                'AppGroup/groupList.html.twig',
                array('app_groups' => $appGroups, 'total_pages'=>$pageArray[2],'current_page'=> $pageArray[3])
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
                $updates = $appGroup->getAppGroupUpdates()->toArray();

                usort($updates, function($a, $b)
                {
                    return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
                });

                return $this->render(
                    'AppGroup/group.html.twig',
                    array('resource' => $appGroup, 'updates' => $updates)
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
    public function showUsersGroupsAction($slug, Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();
            $appUser = $em->getRepository('AppBundle:AppUser')->find($slug);

            if($appUser === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                $total_count = count($appUser->getAppGroups());

                $paginator = new Paginator();

                $pageArray = $paginator->getPagination($request, $total_count);

                $appGroups = $em->getRepository('AppBundle:AppGroup')->getUsersAppGroups($appUser, $pageArray[0], $pageArray[1]);

                return $this->render(
                    'AppGroup/usersGroupList.html.twig',
                    array('resource' => $appUser, 'app_groups' => $appGroups, 'total_pages'=>$pageArray[2],'current_page'=> $pageArray[3])
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/groups/{slug}/members", name="group_members")
     */
    public function showAppGroupMembersAction($slug, Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();
            $appGroup = $em->getRepository('AppBundle:AppGroup')->find($slug);

            if($appGroup === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                $total_count = count($appGroup->getAppUsers());

                $paginator = new Paginator();

                $pageArray = $paginator->getPagination($request, $total_count);

                $appGroupMembers = $em->getRepository('AppBundle:AppUser')->getAppGroupMembers($appGroup, $pageArray[0], $pageArray[1]);

                return $this->render(
                    'AppGroup/groupMemberList.html.twig',
                    array('resource' => $appGroup, 'list' => $appGroupMembers, 'total_pages'=>$pageArray[2],'current_page'=> $pageArray[3])
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
            $em = $this->getDoctrine()->getManager();

            $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $appGroup = new AppGroup();

            $form = $this->createForm(
                new AppGroupType(),
                $appGroup,
                array('action' => $this->generateUrl('group_create'))
            );

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $appGroup = $form->getData();

                $appGroup->setCreator($appUser);

                $appGroup->setCreationDate(new DateTime());

                $em->persist($appGroup);
                $em->flush();

                return $this->redirectToRoute('homepage');
            }

            return $this->render(
                'AppGroup/register.html.twig',
                array('resource' => $appUser, 'form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }
}