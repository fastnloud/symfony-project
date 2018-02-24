<?php

namespace AppBundle\Entity;

use Sanity\BlockContent;

/**
 * Class Timeline
 * @package AppBundle\Entity
 */
class Timeline extends AbstractSanity
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $subtitle;

    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime|null
     */
    protected $startDate;

    /**
     * @var \DateTime|null
     */
    protected $endDate;

    /**
     * @return string
     */
    public function getDescription()
    {
        return BlockContent::toHtml($this->description, $this->getClient()->config());
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndDate()
    {
        return !empty($this->endDate) ? new \DateTime($this->endDate) : null;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartDate()
    {
        return !empty($this->startDate) ? new \DateTime($this->startDate) : null;
    }
}