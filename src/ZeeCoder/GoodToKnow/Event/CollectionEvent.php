<?php

namespace ZeeCoder\GoodToKnow\Event;

use Symfony\Component\EventDispatcher\Event;

class CollectionEvent extends Event
{
    private $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param mixed $collection
     */
    public function setCollection($collection)
    {
        $this->collection = $collection;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCollection()
    {
        return $this->collection;
    }
}
