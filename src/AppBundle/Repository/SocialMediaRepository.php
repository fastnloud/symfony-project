<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class SocialMediaRepository
 * @package AppBundle\Repository
 */
class SocialMediaRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllOrderedByPriority()
    {
        return $this->getEntityManager()
                    ->createQuery('SELECT sm FROM AppBundle:SocialMedia sm ORDER BY sm.priority ASC')
                    ->getResult();
    }
}