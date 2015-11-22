<?php

namespace ZeeCoder\GoodToKnow\Listener;

use ZeeCoder\GoodToKnow\Event\TransformEvent;
use ZeeCoder\GoodToKnow\ParameterInjector;

/**
 * Uses the ParameterInjector to inject parameters to Fact texts.
 */
class ParameterListener
{
    private $parameterInjector;

    public function __construct(ParameterInjector $parameterInjector)
    {
        $this->parameterInjector = $parameterInjector;
    }

    public function onTransform(TransformEvent $event)
    {
        $fact = $event->getFact();

        $fact->setText($this->parameterInjector->inject($fact->getText()));
    }
}
