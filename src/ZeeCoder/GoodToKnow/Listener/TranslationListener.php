<?php

namespace ZeeCoder\GoodToKnow\Listener;

use Symfony\Component\Translation\TranslatorInterface;
use ZeeCoder\GoodToKnow\Event\TransformEvent;

/**
 * Uses the translator to translate Fact texts.
 */
class TranslationListener
{
    private $translator;
    private $translationGroup;

    public function __construct(TranslatorInterface $translator, $translationGroup = 'good_to_know')
    {
        $this->translator = $translator;
        $this->translationGroup = $translationGroup;
    }

    public function onTransform(TransformEvent $event)
    {
        $fact = $event->getFact();

        $fact->setText($this->translator->trans($fact->getText(), [], $this->translationGroup));
    }
}
