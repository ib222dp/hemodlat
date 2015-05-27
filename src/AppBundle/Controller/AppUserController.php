<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AppUser;
use AppBundle\Helper\Paginator;

class AppUserController extends Controller
{
     /**
     * @Route("/users", name="users")
     */
    public function showUsersAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $form = $this->createFormBuilder()
                ->add('fullName', 'text')
                ->getForm();

            if ($request->isMethod('POST'))
            {
                $form->handleRequest($request);

                $formParams = $form->getData();

                $fullName = $formParams['fullName'];

                if(isset($fullName) && (!empty($fullName)))
                {
                    $appUsers= $em->getRepository('AppBundle:AppUser')->getAppUsersByName($fullName);

                    return $this->render(
                        'AppUser/userSearchList.html.twig',
                        array('list' => $appUsers, 'form' => $form->createView())
                    );
                }
                else
                {
                    $total_count = $em->getRepository('AppBundle:AppUser')->getAppUsersCount();

                    $paginator = new Paginator();

                    $pageArray = $paginator->getPagination($request, $total_count);

                    $appUsers = $em->getRepository('AppBundle:AppUser')->getPaginatedAppUsers($pageArray[0], $pageArray[1]);

                    return $this->render(
                        'AppUser/userList.html.twig',
                        array('list' => $appUsers, 'total_pages' => $pageArray[2], 'current_page' => $pageArray[3], 'form' => $form->createView())
                    );
                }
            }
            else
            {
                $total_count = $em->getRepository('AppBundle:AppUser')->getAppUsersCount();

                $paginator = new Paginator();

                $pageArray = $paginator->getPagination($request, $total_count);

                $appUsers = $em->getRepository('AppBundle:AppUser')->getPaginatedAppUsers($pageArray[0], $pageArray[1]);

                return $this->render(
                    'AppUser/userList.html.twig',
                    array('list' => $appUsers, 'total_pages' => $pageArray[2], 'current_page' => $pageArray[3], 'form' => $form->createView())
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("users/{slug}", name="user_show")
     */
    public function showUserAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

            if($appUser === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                $loggedInUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

                if ($appUser == $loggedInUser)
                {
                    return $this->redirectToRoute('profile_show');
                }
                else
                {
                    if ($loggedInUser->getFriendships()->isEmpty())
                    {
                        return $this->render(
                            'AppUser/user.html.twig',
                            array('resource' => $appUser)
                        );
                    }
                    else
                    {
                        foreach ($loggedInUser->getFriendships() as $friendship)
                        {
                            if ($friendship->getFriendUser() == $appUser)
                            {
                                if ($friendship->getFriendshipType()->getFshipType() == "Pending")
                                {
                                    return $this->render(
                                        'AppUser/pendingUser.html.twig',
                                        array('resource' => $appUser)
                                    );
                                }
                                elseif ($friendship->getFriendshipType()->getFshipType() == "Accepted")
                                {
                                    return $this->redirect($this->generateUrl('friend_show', array('slug' => $slug)));
                                }
                                elseif ($friendship->getFriendshipType()->getFshipType() == "Asked")
                                {
                                    return $this->render(
                                        'AppUser/askedUser.html.twig',
                                        array('resource' => $appUser)
                                    );
                                }
                            }
                            else
                            {
                                continue;
                            }
                        }

                        return $this->render(
                            'AppUser/user.html.twig',
                            array('resource' => $appUser)
                        );
                    }
                }
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("users/{slug}/info", name="users_info")
     */
    public function showUserInfoAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($slug);

            if($appUser === null)
            {
                throw $this->createNotFoundException();
            }
            else
            {
                return $this->render(
                    'AppUser/userInfo.html.twig',
                    array('resource' => $appUser)
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}