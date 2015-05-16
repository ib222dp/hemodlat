<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AppUser;
use AppBundle\Form\Type\AppUserType;
use AppBundle\Entity\ProfilePicture;

class AppUserController extends Controller
{
     /**
     * @Route("/users", name="users")
     */
    public function showUsersAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $appUsers = $this->getDoctrine()->getRepository('AppBundle:AppUser')->findAll();

            return $this->render(
                'Friend/userList.html.twig',
                array('app_users' => $appUsers)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/profile", name="profile_show")
     */
    public function showProfileAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $loggedInUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $updates = $loggedInUser->getStatusUpdates()->toArray();

            usort($updates, function($a, $b)
            {
                return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
            });

            return $this->render(
                'AppUser/profile.html.twig',
                array('app_user' => $loggedInUser, 'updates' => $updates)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/profilepic/upload", name="profilepic_upload")
     */
    public function uploadProfilePicAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $profilePicture = new ProfilePicture();

            $form = $this->createFormBuilder($profilePicture)
                ->setAction($this->generateUrl('profilepic_upload'))
                ->add('name')
                ->add('file')
                ->getForm();

            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            if ($form->isValid())
            {
                $em->persist($profilePicture);
                $em->flush();

                $appUser->setProfilePicture($profilePicture);
                $em->persist($appUser);
                $em->flush();

                return $this->redirectToRoute('profile_show');
            }

            return $this->render(
                'AppUser/uploadprofilepic.html.twig',
                array('app_user' => $appUser, 'form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function editProfileAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $loggedInUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $form = $this->createForm(
                new AppUserType(),
                $loggedInUser,
                array('action' => $this->generateUrl('profile_edit'))
            );

            $form->remove('username');
            $form->remove('email');
            $form->remove('password', 'repeated');

            $form->handleRequest($request);

            if ($form->isValid())
            {
                $loggedInUser->setUsername($loggedInUser->getUsername());
                $loggedInUser->setEmail($loggedInUser->getEmail());
                $loggedInUser->setPassword($loggedInUser->getPassword());
                $loggedInUser->setProfilePicture($loggedInUser->getProfilePicture());

                $em->persist($loggedInUser);
                $em->flush();

                return $this->redirectToRoute('profile_show');
            }

            return $this->render(
                'AppUser/editprofile.html.twig',
                array('app_user' => $loggedInUser, 'form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}