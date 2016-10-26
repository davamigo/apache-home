<?php

namespace Test\Davamigo\ApacheHome\Model;

use Davamigo\ApacheHome\Model\Ports;

/**
 * Test for Ports Model
 *
 * @package Test\Davamigo\ApacheHome\Model
 * @author David Amigo <davamigo@gmail.com>
 * @group Test_Unit_Davamigo_ApacheHome_Model_Ports
 * @group Test_Unit_Davamigo_ApacheHome_Model
 * @group Test_Unit_Davamigo_ApacheHome
 * @group Test_Unit_Davamigo
 * @group Test_Unit
 * @group Test
 */
class PortsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic model test
     */
    public function testBasic()
    {
        // Configure the test case
        $model = new Ports();

        // Assertions
        $this->assertInternalType('array', $model->getPorts());
    }

    /**
     * Test scanPorts method
     */
    public function testScanPorts()
    {
        // Test data
        $data  = 'tcp6  0  0 ::1:25    :::*  LISTEN  -  ' . PHP_EOL;
        $data .= 'tcp6  0  0 :::8080   :::*  LISTEN  -  ' . PHP_EOL;
        $data .= 'tcp6  0  0 :::17500  :::*  LISTEN  -  ' . PHP_EOL;
        $data .= 'tcp6  0  0 :::80     :::*  LISTEN  -  ' . PHP_EOL;
        $data .= 'tcp6  0  0 :::8000   :::*  LISTEN  -  ' . PHP_EOL;

        $expected = array(
            '8080',
            '17500',
            '80',
            '8000'
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Ports')
            ->disableOriginalConstructor()
            ->setMethods(array('netstat'))
            ->getMock();

        /** @var Ports $model */
        $model = $mock;

        // Method netstat() will return $data
        $mock
            ->expects($this->once())
            ->method('netstat')
            ->willReturn($data);

        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Ports');
        $method = $reflection->getMethod('scanPorts');
        $method->setAccessible(true);
        $method->invoke($mock);

        // Assertions
        $this->assertEquals($expected, $model->getPorts());
    }

    /**
     * Test sortPorts method
     */
    public function testSortPorts()
    {
        // Test data
        $data = array(
            '8080',
            '17500',
            '80',
            '8000',
            '88'
        );

        $expected = array(
            '80',
            '88',
            '8000',
            '8080',
            '17500'
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Ports')
            ->disableOriginalConstructor()
            ->getMock();

        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Ports');
        $property = $reflection->getProperty('ports');
        $property->setAccessible(true);
        $property->setValue($mock, $data);

        $method = $reflection->getMethod('sortPorts');
        $method->setAccessible(true);
        $method->invoke($mock);

        // Assertions
        $this->assertEquals($expected, $property->getValue($mock));
    }
}
