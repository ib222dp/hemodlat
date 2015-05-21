<?php

namespace AppBundle\Helper;

use Symfony\Component\HttpFoundation\Request;

class Paginator
{
    //http://symfonysymplifyd.blogspot.se/search/label/Pagination
    //http://inchoo.net/dev-talk/paginator-symfony2-beta/
    public function getPagination(Request $request, $total_count)
    {
        $page = $request->get('page');

        if(!is_numeric($page))
        {
            $page = 1;
        }
        else
        {
            $page = floor($page);
        }

        $count_per_page = 3;

        $total_pages = ceil($total_count/$count_per_page);

        if($total_count <= $count_per_page)
        {
            $page = 1;
        }

        if(($page * $count_per_page) > $total_count)
        {
            $page = $total_pages;
        }

        $offset = 0;

        if($page > 1)
        {
            $offset = $count_per_page * ($page - 1);
        }

        return array($offset, $count_per_page, $total_pages, $page);
    }

}