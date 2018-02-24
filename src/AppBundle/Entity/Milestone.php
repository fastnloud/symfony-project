<?php

namespace AppBundle\Entity;

use Sanity\BlockContent;

/**
 * Class Milestone
 * @package AppBundle\Entity
 */
class Milestone extends AbstractSanity
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var \DateTime|null
     */
    protected $date;

    /**
     * @var array
     */
    protected $tags;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return \DateTime|null
     */
    public function getDate()
    {
        return !empty($this->date) ? new \DateTime($this->date) : null;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }
}