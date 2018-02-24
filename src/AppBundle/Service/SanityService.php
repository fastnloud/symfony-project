<?php

namespace AppBundle\Service;

use AppBundle\Entity\Author;
use AppBundle\Entity\Link;
use AppBundle\Entity\Milestone;
use AppBundle\Entity\Placeholder;
use AppBundle\Entity\SanityInterface;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Timeline;
use Sanity\Client;

/**
 * Class SanityService
 * @package AppBundle\Service
 */
class SanityService
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $types = [
        'author'      => Author::class,
        'link'        => Link::class,
        'timeline'    => Timeline::class,
        'milestone'   => Milestone::class,
        'tag'         => Tag::class,
        'placeholder' => Placeholder::class
    ];

    /**
     * @param $projectId
     * @param $dataset
     */
    public function __construct($projectId, $dataset)
    {
        $this->client = new Client([
            'projectId' => $projectId,
            'dataset'   => $dataset,
        ]);
    }

    /**
     * @return array
     */
    public function fetchAll()
    {
        $result = $this->client->fetch(<<<QUERY
{
    "author": *[_type == 'author']{_id, _type, _createdAt, _updatedAt, name, title, bio, 'image': image.asset->url},
    "link": *[_type == 'link']{_id, _type, _createdAt, _updatedAt, label, icon, href, priority},
    "timeline": *[_type == 'timeline']{_id, _type, _createdAt, _updatedAt, title, subtitle, location, description, startDate, endDate},
    "milestone": *[_type == 'milestone']{_id, _type, _createdAt, _updatedAt, title, description, date, 'tags': tags[]->label},
    "tag": *[_type == 'tag']{_id, _type, _createdAt, _updatedAt, label},
    "placeholder": *[_type == 'placeholder']{_id, _type, _createdAt, _updatedAt, name, value}
}
QUERY
);

        return $this->convert($this->mergeResult($result));
    }

    /**
     * @param array $result
     * @param SanityInterface $class
     * @return array
     */
    public function convert(array $result = [], SanityInterface $class = null)
    {
        $data = [];

        if ($result) {
            foreach($result as $key => $value) {
                if ($class) {
                    /** @var SanityInterface $obj */
                    $obj = new $class();
                    $obj->init($this->client, $value);
                } elseif (isset($result[$key]['_type']) && array_key_exists($result[$key]['_type'], $this->types)) {
                    /** @var SanityInterface $obj */
                    $obj = new $this->types[$result[$key]['_type']]();
                    $obj->init($this->client, $value);
                }

                if (isset($obj)) {
                    $fullyQualifiedClassName = get_class($obj);
                    $className = substr($fullyQualifiedClassName, strrpos($fullyQualifiedClassName, '\\') + 1);

                    if ($key = $obj->getKey()) {
                        $data[$className][$key] = $obj;
                    } else {
                        $data[$className][] = $obj;
                    }
                }

                $obj = null; // reset
            }
        }

        return $data;
    }

    /**
     * @param array $result
     * @return array
     */
    public function mergeResult(array $result = [])
    {
        $mergedResult = [];

        foreach ($result as $data) {
            if (is_array($data)) {
                foreach ($data as $value) {
                    $mergedResult[] = $value;
                }
            }
        }

        return $mergedResult;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}