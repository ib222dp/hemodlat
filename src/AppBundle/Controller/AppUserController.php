<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\AppUser;
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
        $profilePicture = new ProfilePicture();

        $form = $this->createFormBuilder($profilePicture)
            ->setAction($this->generateUrl('profilepic_upload'))
            ->add('name')
            ->add('file')
            ->add('Skicka', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($profilePicture);
            $em->flush();

            $appUser = $em->getRepository('AppBundle:AppUser')->find($this->getUser()->getId());

            $appUser->setProfilePicture($profilePicture);

            $em->persist($appUser);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'AppUser/uploadprofilepic.html.twig',
            array('form' => $form->createView())
        );
    }

}