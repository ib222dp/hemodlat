<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CropController extends Controller
{
    /**
     * @Route("/crops", name="crops")
     */
    public function showCropsAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $crops = $this->getDoctrine()->getRepository('AppBundle:Crop')->findAll();

            return $this->render(
                'Crop/cropList.html.twig',
                array('crops' => $crops)
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/crops/{slug}", name="crop_show")
     */
    public function showCropAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $crop = $this->getDoctrine()->getRepository('AppBundle:Crop')->find($slug);

            if($crop === null)
            {
                return $this->createNotFoundException();
            }
            else
            {
                return $this->render(
                    'Crop/crop.html.twig',
                    array('crop' => $crop)
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/users/{slug}/crops", name="users_crops")
     */
    public function showUsersCropsAction($slug)
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
                $crops = $appUser->getCrops();

                return $this->render(
                    'Crop/userscropList.html.twig',
                    array('crops' => $crops)
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }
}