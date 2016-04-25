<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class LastRouteListener
{
    //http://symfony.vojtechvondra.cz/en/2014-08-28-saving-the-last-matched-route
    public function onKernelRequest(GetResponseEvent $event)
    {

        if ($event->getRequestType() !== HttpKernel::MASTER_REQUEST) {
            return;
        }

        $request = $event->getRequest();
        $session = $request->getSession();

        $routeName = $request->get('_route');
        $routeParams = $request->get('_route_params');
        if ($routeName[0] == '_') {
            return;
        }
        $routeData = ['name' => $routeName, 'params' => $routeParams];


        $thisRoute = $session->get('this_route', []);
        if ($thisRoute == $routeData) {
            return;
        }
        $session->set('last_route', $thisRoute);
        $session->set('this_route', $routeData);
    }
}
