<?php

namespace Test\Davamigo\ApacheHome\Model;

use Davamigo\ApacheHome\Entity\VirtualHost;
use Davamigo\ApacheHome\Model\VirtualHosts;

/**
 * Test for VirtualHosts Model
 *
 * @package Test\Davamigo\ApacheHome\Model
 * @author David Amigo <davamigo@gmail.com>
 * @group Test_Unit_Davamigo_ApacheHome_Model_Files
 * @group Test_Unit_Davamigo_ApacheHome_Model
 * @group Test_Unit_Davamigo_ApacheHome
 * @group Test_Unit_Davamigo
 * @group Test_Unit
 * @group Test
 */
class VirtualHostsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic model test
     */
    public function testBasic()
    {
        // Configure the test case
        $model = new VirtualHosts();

        // Assertions
        $this->assertInternalType('array', $model->getVirtualHosts());
    }

    /**
     * Test scan virtual host in a buffer
     */
    public function testScanVirtualHostsWorksProperly()
    {
        // Test data
        $fileName = 'a_vhost_file.conf';
        $serverName = 'www.my-server.com';
        $documentRoot = '/var/www/my-server/';
        $directoryIndex = 'index.php';

        $data = implode(PHP_EOL, array(
            '<VirtualHost *:80>',
            'ServerName ' . $serverName,
            'DocumentRoot ' . $documentRoot,
            'DirectoryIndex ' . $directoryIndex,
            'ErrorLog /var/www/my-server/error.log',
            'CustomLog /var/www/my-server/access.log combined',
            '</VirtualHost>'
        ));

        $expected = array(
            new VirtualHost($fileName, $serverName, $documentRoot, $directoryIndex)
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\VirtualHosts')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\VirtualHosts');
        $property = $reflection->getProperty('virtualHosts');
        $property->setAccessible(true);
        $property->setValue($mock, array());

        $method = $reflection->getMethod('scanVirtualHostsInBuffer');
        $method->setAccessible(true);
        $count = $method->invoke($mock, $data, $fileName);

        // Assertions
        $this->assertEquals(1, $count);
        $this->assertEquals($expected, $property->getValue($mock));
    }

    /**
     * Test scan virtual host in a buffer
     */
    public function testScanVirtualHostsWithoutEndTag()
    {
        // Test data
        $fileName = 'a_vhost_file.conf';
        $serverName = 'www.my-server.com';
        $documentRoot = '/var/www/my-server/';
        $directoryIndex = 'index.php';

        $data = implode(PHP_EOL, array(
            '<VirtualHost *:80>',
            'ServerName ' . $serverName,
            'DocumentRoot ' . $documentRoot,
            'DirectoryIndex ' . $directoryIndex,
            'ErrorLog /var/www/my-server/error.log',
            'CustomLog /var/www/my-server/access.log combined',
            '</VirtualHost'
        ));

        $expected = array();

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\VirtualHosts')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\VirtualHosts');
        $property = $reflection->getProperty('virtualHosts');
        $property->setAccessible(true);
        $property->setValue($mock, array());

        $method = $reflection->getMethod('scanVirtualHostsInBuffer');
        $method->setAccessible(true);
        $count = $method->invoke($mock, $data, $fileName);

        // Assertions
        $this->assertEquals(0, $count);
        $this->assertEquals($expected, $property->getValue($mock));
    }

    /**
     * Test scan virtual host in a buffer
     */
    public function testScanVirtualHostsWithoutServerName()
    {
        // Test data
        $fileName = 'a_vhost_file.conf';
        $documentRoot = '/var/www/my-server/';
        $directoryIndex = 'index.php';

        $data = implode(PHP_EOL, array(
            '<VirtualHost *:80>',
            'DocumentRoot ' . $documentRoot,
            'DirectoryIndex ' . $directoryIndex,
            '</VirtualHost>'
        ));

        $expected = array();

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\VirtualHosts')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\VirtualHosts');
        $property = $reflection->getProperty('virtualHosts');
        $property->setAccessible(true);
        $property->setValue($mock, array());

        $method = $reflection->getMethod('scanVirtualHostsInBuffer');
        $method->setAccessible(true);
        $count = $method->invoke($mock, $data, $fileName);

        // Assertions
        $this->assertEquals(0, $count);
        $this->assertEquals($expected, $property->getValue($mock));
    }

    /**
     * Test scan virtual host in a buffer
     */
    public function testScanVirtualHostsWithoutDocumentRoot()
    {
        // Test data
        $fileName = 'a_vhost_file.conf';
        $serverName = 'www.my-server.com';
        $directoryIndex = 'index.php';

        $data = implode(PHP_EOL, array(
            '<VirtualHost *:80>',
            'ServerName ' . $serverName,
            'DirectoryIndex ' . $directoryIndex,
            'ErrorLog /var/www/my-server/error.log',
            'CustomLog /var/www/my-server/access.log combined',
            '</VirtualHost>'
        ));

        $expected = array();

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\VirtualHosts')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\VirtualHosts');
        $property = $reflection->getProperty('virtualHosts');
        $property->setAccessible(true);
        $property->setValue($mock, array());

        $method = $reflection->getMethod('scanVirtualHostsInBuffer');
        $method->setAccessible(true);
        $count = $method->invoke($mock, $data, $fileName);

        // Assertions
        $this->assertEquals(0, $count);
        $this->assertEquals($expected, $property->getValue($mock));
    }

    /**
     * Test scan virtual host in a buffer
     */
    public function testScanVirtualHostsWithoutDirectoryIndex()
    {
        // Test data
        $fileName = 'a_vhost_file.conf';
        $serverName = 'www.my-server.com';
        $documentRoot = '/var/www/my-server/';

        $data = implode(PHP_EOL, array(
            '<VirtualHost *:80>',
            'ServerName ' . $serverName,
            'DocumentRoot ' . $documentRoot,
            'ErrorLog /var/www/my-server/error.log',
            'CustomLog /var/www/my-server/access.log combined',
            '</VirtualHost>'
        ));

        $expected = array(
            new VirtualHost($fileName, $serverName, $documentRoot, "")
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\VirtualHosts')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\VirtualHosts');
        $property = $reflection->getProperty('virtualHosts');
        $property->setAccessible(true);
        $property->setValue($mock, array());

        $method = $reflection->getMethod('scanVirtualHostsInBuffer');
        $method->setAccessible(true);
        $count = $method->invoke($mock, $data, $fileName);

        // Assertions
        $this->assertEquals(1, $count);
        $this->assertEquals($expected, $property->getValue($mock));
    }

    /**
     * Test scan virtual host in a buffer
     */
    public function testScanVirtualHostsWithMotheThanOneVirtualHost()
    {
        // Test data
        $fileName = 'a_vhost_file.conf';
        $serverName1 = 'www.my-server.com';
        $documentRoot1 = '/var/www/my-server/';
        $directoryIndex1 = 'index.php';
        $serverName2 = 'www.other-server.com';
        $documentRoot2 = '/var/www/other-server/';
        $directoryIndex2 = 'index.py';

        $data = implode(PHP_EOL, array(
            '<VirtualHost *:80>',
            'ServerName ' . $serverName1,
            'DocumentRoot ' . $documentRoot1,
            'DirectoryIndex ' . $directoryIndex1,
            'ErrorLog /var/www/my-server/error.log',
            'CustomLog /var/www/my-server/access.log combined',
            '</VirtualHost>',
            PHP_EOL,
            '<VirtualHost *:80>',
            'ServerName ' . $serverName2,
            'DocumentRoot ' . $documentRoot2,
            'DirectoryIndex ' . $directoryIndex2,
            'ErrorLog /var/www/my-server/error.log',
            'CustomLog /var/www/my-server/access.log combined',
            '</VirtualHost>'
        ));

        $expected = array(
            new VirtualHost($fileName, $serverName1, $documentRoot1, $directoryIndex1),
            new VirtualHost($fileName, $serverName2, $documentRoot2, $directoryIndex2)
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\VirtualHosts')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\VirtualHosts');
        $property = $reflection->getProperty('virtualHosts');
        $property->setAccessible(true);
        $property->setValue($mock, array());

        $method = $reflection->getMethod('scanVirtualHostsInBuffer');
        $method->setAccessible(true);
        $count = $method->invoke($mock, $data, $fileName);

        // Assertions
        $this->assertEquals(2, $count);
        $this->assertEquals($expected, $property->getValue($mock));
    }

    /**
     * Test scan virtual host in a buffer
     */
    public function testScanVirtualHostsWithInvalidChars()
    {
        // Test data
        $fileName = 'a_vhost_file.conf';
        $serverName = 'www.my-server.com';
        $documentRoot = '/var/www/my-server/';
        $directoryIndex = 'index.php';

        $data = implode(PHP_EOL, array(
            '<VirtualHost *:80>',
            '#ServerName ' . $serverName,
            'DocumentRoot ' . $documentRoot,
            'DirectoryIndex% ' . $directoryIndex,
            'ErrorLog /var/www/my-server/error.log',
            'CustomLog /var/www/my-server/access.log combined',
            '</VirtualHost>'
        ));

        $expected = array();

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\VirtualHosts')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\VirtualHosts');
        $property = $reflection->getProperty('virtualHosts');
        $property->setAccessible(true);
        $property->setValue($mock, array());

        $method = $reflection->getMethod('scanVirtualHostsInBuffer');
        $method->setAccessible(true);
        $count = $method->invoke($mock, $data, $fileName);

        // Assertions
        $this->assertEquals(0, $count);
        $this->assertEquals($expected, $property->getValue($mock));
    }
}
