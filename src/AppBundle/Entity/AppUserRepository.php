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

    public function getUsersCrops($appUser, $offset, $count_per_page)
    {

    }

}
