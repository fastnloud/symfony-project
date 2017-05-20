<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class SkillRepository
 * @package AppBundle\Repository
 */
class SkillRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllOrderedByRand()
    {
        $result = $this->getEntityManager()
                       ->createQuery('SELECT s FROM AppBundle:Skill s')
                       ->getResult();

        shuffle($result);

        return $result;
    }
}