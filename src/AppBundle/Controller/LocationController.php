<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Location;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LocationController extends Controller
{
    /**
     * @Route("/locations", name="locations")
     */
    public function showLocationsAction()
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $locations = $this->getDoctrine()->getRepository('AppBundle:Location')->findAll();

            return $this->render(
                'Location/locationList.html.twig', array(
                'locations' => $locations,
            ));
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }

    /**
     * @Route("/locations/{slug}", name="location_show")
     */
    public function showLocationAction($slug)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $location = $this->getDoctrine()->getRepository('AppBundle:Location')->find($slug);

            return $this->render(
                'Location/location.html.twig', array(
                'location' => $location,
            ));
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }
}