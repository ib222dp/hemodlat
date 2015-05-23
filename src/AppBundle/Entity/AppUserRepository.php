<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AppUserRepository extends EntityRepository
{
    //http://symfonysymplifyd.blogspot.se/search/label/Pagination
    //http://inchoo.net/dev-talk/paginator-symfony2-beta/

    public function getAppUsersCount()
    {
        $total = $this->getEntityManager()->createQueryBuilder()
            ->select('Count(u)')
            ->from('AppBundle:AppUser', 'u')
            ->getQuery()
            ->getSingleScalarResult();

        return $total;
    }

    public function getPaginatedAppUsers($offset, $count_per_page)
    {
        $appUsers = $this->getEntityManager()->createQueryBuilder()
            ->select('u')
            ->from('AppBundle:AppUser', 'u')
            ->orderBy('u.username', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($count_per_page)
            ->getQuery()
            ->getResult();

        return $appUsers;
    }

    public function getCropGrowers($crop, $offset, $count_per_page)
    {
        $dql = "SELECT u FROM AppBundle:AppUser u INNER JOIN u.crops c WHERE c= ?1 ORDER BY u.username ASC";

        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter(1, $crop)
            ->setFirstResult($offset)
            ->setMaxResults($count_per_page);

        $cropGrowers = $query->getResult();
        return $cropGrowers;
    }

    public function getAppGroupMembers($appGroup, $offset, $count_per_page)
    {
        $dql = "SELECT u FROM AppBundle:AppUser u INNER JOIN u.appGroups g WHERE g= ?1 ORDER BY u.username ASC";

        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter(1, $appGroup)
            ->setFirstResult($offset)
            ->setMaxResults($count_per_page);

        $appGroupMembers = $query->getResult();
        return $appGroupMembers;
    }

}
