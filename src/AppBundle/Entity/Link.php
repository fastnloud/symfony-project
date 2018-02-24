<?php

namespace AppBundle\Entity;

/**
 * Class Link
 * @package AppBundle\Entity
 */
class Link extends AbstractSanity
{
    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $icon;

    /**
     * @var string
     */
    protected $href;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return (int) $this->priority;
    }
}