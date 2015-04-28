<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\RegistrationType;
use AppBundle\Form\Model\Registration;
use AppBundle\Entity\AppUser;

class AccountController extends Controller
{
    public function registerAction()
    {
        $registration = new Registration();
        $form = $this->createForm(new RegistrationType(), $registration, array(
            'action' => $this->generateUrl('account_create'),
        ));

        return $this->render(
            'Account/register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(new RegistrationType(), new Registration());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();

            $appUser = $registration->getAppUser();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($appUser, $appUser->getPassword());

            $appUser->setPassword($encoded);


            $em->persist($appUser);
            $em->flush();

            return $this->redirectToRoute('login_route');
        }

        return $this->render(
            'Account/register.html.twig',
            array('form' => $form->createView())
        );
    }
}