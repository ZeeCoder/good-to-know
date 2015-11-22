<?php

namespace ZeeCoder\GoodToKnow;

class ParameterInjectorTest extends \PHPUnit_Framework_TestCase
{
    private $parameterInjector;

    public function setUp()
    {
        $this->parameterInjector = new ParameterInjector;
    }

    public function testNormalUsage()
    {
        $this->parameterInjector
            ->addParameter('%key%', '!key!')
            ->addParameter('%val%', '!val!')
            ->addParameter('%fn%', function() {
                return 'callable';
            });

        $this->assertEquals(
            $this->parameterInjector->inject('Lorem isum dolor %key% sit %key% amet %val% -- %fn%'),
            'Lorem isum dolor !key! sit !key! amet !val! -- callable'
        );
    }

    public function testSetParameters()
    {
        $this->parameterInjector
            ->addParameter('%key%', '!key!')
            ->setParameters([]); // resetting the parameters to an empty array

        $this->assertEquals(
            $this->parameterInjector->inject('%key%%val%%fn%'),
            '%key%%val%%fn%'
        );
    }
}
