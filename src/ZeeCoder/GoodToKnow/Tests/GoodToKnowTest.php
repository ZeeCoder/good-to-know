<?php

namespace ZeeCoder\GoodToKnow;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;

class GoodToKnowTest extends \PHPUnit_Framework_TestCase
{
    public function testGroups()
    {
        $goodToKnow = new GoodToKnow([
            ['text' => 'text', 'group' => 'group'],
            ['text' => 'text2', 'group' => ['group', 'group2']],
            ['text' => 'text3', 'group' => 'group2'],
        ]);

        $this->assertEquals([
            'text', 'text2'
        ], $goodToKnow->getAllByGroup('group'));

        $this->assertEquals([
            'text2', 'text3'
        ], $goodToKnow->getAllByGroup('group2'));

        $this->setExpectedException(
            '\RuntimeException', 'Missing "group" parameter.'
        );

        $goodToKnow->getAllByGroup();
    }

    public function testParameters()
    {
        $goodToKnow = new GoodToKnow([
            ['text' => '%param1% - %param2% - %param3%', 'group' => 'group'],
        ]);

        $goodToKnow->addParameter('%param1%', 'value1');
        $goodToKnow->addParameter('%param2%', 'value2');
        $goodToKnow->addParameter('%param3%', function(){
            return 'value3';
        });

        $this->assertEquals([
            'value1 - value2 - value3'
        ], $goodToKnow->getAllByGroup('group'));
    }

    public function testWithTranslator()
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', [
            'trans_key' => '%param% - text',
        ], 'en', 'custom_trans_domain');

        $goodToKnow = new GoodToKnow([
            ['text' => 'trans_key', 'group' => 'group'],
        ]);
        $goodToKnow->addParameter('%param%', 'paramvalue');
        $goodToKnow->addTranslator($translator, 'custom_trans_domain');

        $this->assertEquals(
            ['paramvalue - text'],
            $goodToKnow->getAllByGroup('group')
        );

        $goodToKnow = new GoodToKnow(
            [['text' => 'trans_key', 'group' => 'group']],
            [
                $translator,
                'custom_trans_domain'
            ]
        );
        $goodToKnow->addParameter('%param%', 'paramvalue');

        $this->assertEquals(
            ['paramvalue - text'],
            $goodToKnow->getAllByGroup('group')
        );
    }
}
