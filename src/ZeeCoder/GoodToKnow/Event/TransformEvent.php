<?php

namespace ZeeCoder\GoodToKnow\Event;

use Symfony\Component\EventDispatcher\Event;
use ZeeCoder\GoodToKnow\Fact;

class TransformEvent extends Event
{
    private $fact;

    public function __construct(Fact $fact)
    {
        $this->fact = $fact;
    }

    /**
     * @param Fact $fact
     */
    public function setFact(Fact $fact)
    {
        $this->fact = $fact;
    }

    /**
     * @return Fact
     */
    public function getFact()
    {
        return $this->fact;
    }
}
