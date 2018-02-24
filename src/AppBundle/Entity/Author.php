<?php

namespace AppBundle\Entity;

use Sanity\BlockContent;

/**
 * Class Author
 * @package AppBundle\Entity
 */
class Author extends AbstractSanity
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $image;

    /**
     * @var string
     */
    protected $bio;

    /**
     * @return string
     */
    public function getBio()
    {
        return BlockContent::toHtml($this->bio, $this->getClient()->config());
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}