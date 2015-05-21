<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AppGroupRepository extends EntityRepository
{
    //http://symfonysymplifyd.blogspot.se/search/label/Pagination
    //http://inchoo.net/dev-talk/paginator-symfony2-beta/

    public function getAppGroupsCount()
    {
        $total = $this->getEntityManager()->createQueryBuilder()
            ->select('Count(g)')
            ->from('AppBundle:AppGroup', 'g')
            ->getQuery()
            ->getSingleScalarResult();

        return $total;
    }

    public function getPaginatedAppGroups($offset, $count_per_page)
    {
        $appGroups = $this->getEntityManager()->createQueryBuilder()
            ->select('g')
            ->from('AppBundle:AppGroup', 'g')
            ->orderBy('g.name', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($count_per_page)
            ->getQuery()
            ->getResult();

        return $appGroups;
    }

}
