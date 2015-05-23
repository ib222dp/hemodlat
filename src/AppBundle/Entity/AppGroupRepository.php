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

    public function getUsersAppGroups($appUser, $offset, $count_per_page)
    {
        $dql = "SELECT g FROM AppBundle:AppGroup g INNER JOIN g.appUsers u WHERE u= ?1 ORDER BY g.name ASC";

        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter(1, $appUser)
            ->setFirstResult($offset)
            ->setMaxResults($count_per_page);

        $appGroups = $query->getResult();
        return $appGroups;
    }

}
