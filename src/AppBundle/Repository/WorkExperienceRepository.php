<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class WorkExperienceRepository
 * @package AppBundle\Repository
 */
class WorkExperienceRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllOrderedByPriority()
    {
        return $this->getEntityManager()
                    ->createQuery('SELECT we FROM AppBundle:WorkExperience we ORDER BY we.priority DESC')
                    ->getResult();
    }
}