<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class EducationRepository
 * @package AppBundle\Repository
 */
class EducationRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllOrderedByPriority()
    {
        return $this->getEntityManager()
                    ->createQuery('SELECT e FROM AppBundle:Education e ORDER BY e.priority ASC')
                    ->getResult();
    }
}