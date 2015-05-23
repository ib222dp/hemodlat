<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CropRepository extends EntityRepository
{
    //http://symfonysymplifyd.blogspot.se/search/label/Pagination
    //http://inchoo.net/dev-talk/paginator-symfony2-beta/

    public function getCropsCount()
    {
        $total = $this->getEntityManager()->createQueryBuilder()
            ->select('Count(c)')
            ->from('AppBundle:Crop', 'c')
            ->getQuery()
            ->getSingleScalarResult();

        return $total;
    }

    public function getPaginatedCrops($offset, $count_per_page)
    {
        $crops = $this->getEntityManager()->createQueryBuilder()
            ->select('c')
            ->from('AppBundle:Crop', 'c')
            ->orderBy('c.name', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($count_per_page)
            ->getQuery()
            ->getResult();

        return $crops;
    }

    public function getUsersCrops($appUser, $offset, $count_per_page)
    {
        $dql = "SELECT c FROM AppBundle:Crop c INNER JOIN c.appUsers u WHERE u= ?1 ORDER BY c.name ASC";

        $query = $this->getEntityManager()->createQuery($dql)
            ->setParameter(1, $appUser)
            ->setFirstResult($offset)
            ->setMaxResults($count_per_page);

        $crops = $query->getResult();
        return $crops;
    }

}
