<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\AppGroup;
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
                'Crop/cropList.html.twig', array(
                'crops' => $crops,
            ));
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
            $em=$this->getDoctrine()->getManager();
            $crop = $em->getRepository('AppBundle:Crop')->find($slug);

            return $this->render(
                'Crop/crop.html.twig',
                array('crop' => $crop )
            );
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }
}