<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\AppUser;
use AppBundle\Form\Type\AppUserType;

class AccountController extends Controller
{
    public function registerAction()
    {
        $appUser = new AppUser();

        $form = $this->createForm(
            new AppUserType(),
            $appUser,
            array('action' => $this->generateUrl('account_create'))
        );

        return $this->render(
            'Account/register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new AppUserType(), new AppUser());

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $appUser = $form->getData();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($appUser, $appUser->getPassword());
            $appUser->setPassword($encoded);

            $appUser->setProfilePicture($em->getRepository('AppBundle:ProfilePicture')->find(1));

            $em->persist($appUser);
            $em->flush();

            $this->addFlash('notice', 'Användarkonto har skapats! Logga in med dina användaruppgifter.');

            return $this->redirectToRoute('login_route');
        }

        return $this->render(
            'Account/register.html.twig',
            array('form' => $form->createView())
        );
    }
}