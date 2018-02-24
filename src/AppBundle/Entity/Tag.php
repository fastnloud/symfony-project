<?php

namespace AppBundle\Entity;

/**
 * Class Tag
 * @package AppBundle\Entity
 */
class Tag extends AbstractSanity
{
    /**
     * @var string
     */
    protected $label;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}