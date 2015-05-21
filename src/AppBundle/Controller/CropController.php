<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Helper\Paginator;

class CropController extends Controller
{
    /**
     * @Route("/crops", name="crops")
     */
    public function showCropsAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $total_count = $em->getRepository('AppBundle:Crop')->getCropsCount();

            $paginator = new Paginator();

            $pageArray = $paginator->getPagination($request, $total_count);

            $crops = $em->getRepository('AppBundle:Crop')->getPaginatedCrops($pageArray[0], $pageArray[1]);

            return $this->render(
                'Crop/cropList.html.twig',
                array('crops' => $crops, 'total_pages'=>$pageArray[2],'current_page'=> $pageArray[3])
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
    public function showUsersCropsAction($slug, Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $em = $this->getDoctrine()->getManager();

            $appUser = $em->getRepository('AppBundle:AppUser')->find($slug);

            if($appUser === null)
            {
                return $this->createNotFoundException();
            }
            else
            {
                $total_count = count($appUser->getCrops());

                $paginator = new Paginator();

                $pageArray = $paginator->getPagination($request, $total_count);

                $crops = $em->getRepository('AppBundle:AppUser')->getUsersCrops($appUser, $pageArray[0], $pageArray[1]);


                return $this->render(
                    'Crop/userscropList.html.twig',
                    array('app_user' => $appUser, 'crops' => $crops, 'total_pages'=>$pageArray[2],'current_page'=> $pageArray[3])
                );
            }
        }
        else
        {
            throw $this->createAccessDeniedException();
        }
    }
}