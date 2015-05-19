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
    public function showAppGroupsAction(Request $request)
    {
        //http://symfonysymplifyd.blogspot.se/search/label/Pagination
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $page = $request->get('page');
            $count_per_page = 2;
            $total_count = $this->getTotalAppGroups();
            $total_pages=ceil($total_count/$count_per_page);

            if(!is_numeric($page))
            {
                $page=1;
            }
            else
            {
                $page=floor($page);
            }
            if($total_count<=$count_per_page)
            {
                $page=1;
            }
            if(($page*$count_per_page)>$total_count)
            {
                $page=$total_pages;
            }

            $offset=0;

            if($page>1)
            {
                $offset = $count_per_page * ($page-1);
            }
            $appGroups = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('g')
                ->from('AppBundle:AppGroup', 'g')
                ->setFirstResult($offset)
                ->setMaxResults($count_per_page)
                ->getQuery()
                ->getArrayResult();

            return $this->render(
                'AppGroup/groupList.html.twig',
                array('app_groups' => $appGroups, 'total_pages'=>$total_pages,'current_page'=> $page)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    public function getTotalAppGroups()
    {
        $em = $this->getDoctrine()->getManager();

        $total = $em->createQueryBuilder()
            ->select('Count(g)')
            ->from('AppBundle:AppGroup', 'g')
            ->getQuery()
            ->getSingleScalarResult();

        return $total;
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
                    array('app_user' => $appUser, 'app_groups' => $appGroups)
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
                array('app_user' => $appUser, 'form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }
}