<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AppUser;
use AppBundle\Form\Type\AppUserType;
use AppBundle\Entity\ProfilePicture;
use AppBundle\Form\Model\Password;
use AppBundle\Form\Type\PasswordType;

class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile_show")
     */
    public function showProfileAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $loggedInUser = $this->getDoctrine()->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            //$updates = $loggedInUser->getStatusUpdates()->toArray();

            $friendUpdates = $loggedInUser->getReceivedFriendUpdates()->toArray();

            //$allUpdates = array_merge($updates, $friendUpdates);

            usort($friendUpdates, function($a, $b)
            {
                return $b->getCreationDate()->format('U') - $a->getCreationDate()->format('U');
            });

            return $this->render(
                'Profile/profile.html.twig',
                array('resource' => $loggedInUser, 'updates' => $friendUpdates)
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
                'Profile/uploadprofilepic.html.twig',
                array('resource' => $appUser, 'form' => $form->createView())
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
                'Profile/editprofile.html.twig',
                array('resource' => $loggedInUser, 'form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/password/change", name="password_change")
     */
    public function changePasswordAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $loggedInUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $passwordModel = new Password();

            $form = $this->createForm(new PasswordType(), $passwordModel);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($loggedInUser, $form->get('newPassword')->getData());

                $loggedInUser->setPassword($encoded);

                $em->persist($loggedInUser);
                $em->flush();

                return $this->redirectToRoute('profile_show');
            }

            return $this->render(
                'Profile/changePassword.html.twig',
                array('resource' => $loggedInUser, 'form' => $form->createView())
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

}