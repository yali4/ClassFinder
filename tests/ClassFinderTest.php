<?php

require '../src/ClassFinder.php';

class ClassFinderTest extends PHPUnit_Framework_TestCase
{

    private $source;

    private function getFinder()
    {

        if ( $this->source ) return $this->source;

        return $this->source = new ClassFinder('../Examples.php');

    }

    public function testCount()
    {

        $this->assertCount(4, $this->getFinder()->getClasses());

    }

    public function testHasClass()
    {

        $this->assertTrue($this->getFinder()->hasClass('Vehicle'));

        $this->assertTrue($this->getFinder()->hasClass('Truck'));

        $this->assertTrue($this->getFinder()->hasClass('Car'));

        $this->assertTrue($this->getFinder()->hasClass('Bus'));

    }

    public function testHasNormalClass()
    {

        $this->assertTrue($this->getFinder()->hasNormalClass('Truck'));

    }

    public function testHasAbstractClass()
    {

        $this->assertTrue($this->getFinder()->hasAbstractClass('Vehicle'));

    }

    public function testHasFinalClass()
    {

        $this->assertTrue($this->getFinder()->hasFinalClass('Car'));

        $this->assertTrue($this->getFinder()->hasFinalClass('Bus'));

    }

    public function testExtends()
    {

        $class = $this->getFinder()->getClass('Car');

        $this->assertSame('Vehicle', $class['extended']);

    }

    public function testImplements()
    {

        $class = $this->getFinder()->getClass('Bus');

        $this->assertEquals(array('BusInterface', 'VehicleInterface'), $class['implemented']);

    }

}