<?php

namespace ZeeCoder\GoodToKnow;

use ZeeCoder\GoodToKnow\Event\CollectionEvent;
use ZeeCoder\GoodToKnow\Event\TransformEvent;
use ZeeCoder\GoodToKnow\Repository\FactObjectRepository;

class ListenerTest extends \PHPUnit_Framework_TestCase
{
    private $gtk;
    private $dispatcher;

    public function setUp()
    {
        $this->gtk = new GoodToKnow(
            (new FactObjectRepository)
                ->attach(
                    (new Fact)
                        ->setText('lorem ipsum')
                        ->addGroup('g1')
                )
                ->attach(
                    (new Fact)
                        ->setText('dolor sit amet')
                        ->addGroup('g1')
                )
                ->attach(
                    (new Fact)
                        ->setText('consectetur adipiscing')
                        ->addGroup('g2')
                )
        );

        $this->dispatcher = $this->gtk->getDispatcher();
    }

    public function testTransformEvent()
    {
        $this->dispatcher->addListener(
            Events::TRANSFORM,
            function (TransformEvent $event) {
                $event->getFact()->setText('change');
            }
        );

        $collection = $this->gtk->findAllByGroups([
            'g1'
        ]);

        foreach ($collection as $fact) {
            $this->assertEquals(
                $fact->getText(),
                'change'
            );
        }
    }

    public function testPrePostTransformEvents()
    {
        $this->dispatcher->addListener(
            Events::PRE_TRANSFORM,
            function (CollectionEvent $event) {
                $collection = $event->getCollection();

                foreach ($collection as $fact) {
                    if ($fact->getText() === 'lorem ipsum') {
                        $collection->detach($fact);
                        break;
                    }
                }
            }
        );

        $this->dispatcher->addListener(
            Events::POST_TRANSFORM,
            function (CollectionEvent $event) {
                $event->getCollection()
                    ->attach(
                        (new Fact)->setText('added')
                    )
                ;
            }
        );

        $collection = $this->gtk->findAllByGroups();
        $collection->rewind();

        $this->assertCount(3, $collection);

        $this->assertEquals('dolor sit amet', $collection->current()->getText());

        $collection->next();
        $this->assertEquals('consectetur adipiscing', $collection->current()->getText());

        $collection->next();
        $this->assertEquals('added', $collection->current()->getText());
    }
}
