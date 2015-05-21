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

}
