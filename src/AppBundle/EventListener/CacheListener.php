<?php

namespace AppBundle\EventListener;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CacheListener
{
    //http://stackoverflow.com/questions/17732454/no-cache-header-with-annotation

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('max-age', 0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);
    }
}