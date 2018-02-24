<?php

namespace AppBundle\Entity;

use Sanity\Client;

/**
 * Class AbstractSanity
 * @package AppBundle\Entity
 */
abstract class AbstractSanity implements SanityInterface
{
    /**
     * @var \Sanity\Client
     */
    private $client;

    /**
     * @var array
     */
    private $input = [];

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $rev;

    /**
     * @var \DateTime|null
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     */
    protected $updatedAt;

    /**
     * @param Client $client
     * @param array $input
     */
    public function init(Client $client, array $input = [])
    {
        $this->client = $client;
        $this->input  = $input;

        foreach(get_class_vars(get_called_class()) as $key => $value) {
            if (!empty($input[$key])) {
                $this->{$key} = $input[$key];
            }
        }

        $this->id        = !empty($input['_id']) ? $input['_id'] : null;
        $this->rev       = !empty($input['_rev']) ? $input['_rev'] : null;
        $this->createdAt = !empty($input['_createdAt']) ? new \DateTime($input['_createdAt']) : null;
        $this->updatedAt = !empty($input['_updatedAt']) ? new \DateTime($input['_updatedAt']) : null;
    }

    /**
     * @return \Sanity\Client
     */
    protected function getClient()
    {
        return $this->client;
    }

    /**
     * @return array
     */
    protected function getInput()
    {
        return (array) $this->input;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return null;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRev()
    {
        return $this->rev;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}