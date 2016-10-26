<?php

namespace Test\Davamigo\ApacheHome\Entity;

use Davamigo\ApacheHome\Entity\VirtualHost;

/**
 * Test for VirtualHost Entity
 *
 * @package Test\Davamigo\ApacheHome\Entity
 * @author David Amigo <davamigo@gmail.com>
 * @group Test_Unit_Davamigo_ApacheHome_Entity_VirtualHost
 * @group Test_Unit_Davamigo_ApacheHome_Entity
 * @group Test_Unit_Davamigo_ApacheHome
 * @group Test_Unit_Davamigo
 * @group Test_Unit
 * @group Test
 */
class VirtualHostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic entity test
     */
    public function testNewEntity()
    {
        // Configure the test case
        $entity = new VirtualHost();

        // Assertions
        $this->assertEmpty($entity->getFileName());
        $this->assertEmpty($entity->getServerName());
        $this->assertEmpty($entity->getDocumentRoot());
        $this->assertEmpty($entity->getDirectoryIndex());
    }

    /**
     * Test entity constructor
     */
    public function testEntityConstructor()
    {
        // Configure the test case
        $entity = new VirtualHost('101', '102', '103', '104');

        // Assertions
        $this->assertEquals('101', $entity->getFileName());
        $this->assertEquals('102', $entity->getServerName());
        $this->assertEquals('103', $entity->getDocumentRoot());
        $this->assertEquals('104', $entity->getDirectoryIndex());
    }

    /**
     * Test setters
     */
    public function testSetters()
    {
        // Configure the test case
        $entity = new VirtualHost();

        // Expected result
        $entity->setFileName('101');
        $entity->setServerName('102');
        $entity->setDocumentRoot('103');
        $entity->setDirectoryIndex('104');

        // Assertions
        $this->assertEquals('101', $entity->getFileName());
        $this->assertEquals('102', $entity->getServerName());
        $this->assertEquals('103', $entity->getDocumentRoot());
        $this->assertEquals('104', $entity->getDirectoryIndex());
    }
}
