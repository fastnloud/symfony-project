<?php

namespace AppBundle\Entity;

use Sanity\Client;

/**
 * Interface SanityInterface
 * @package AppBundle\Entity
 */
interface SanityInterface
{
    /**
     * @param Client $client
     * @param array $input
     * @return void
     */
    public function init(Client $client, array $input = []);

    /**
     * @return mixed
     */
    public function getKey();
}