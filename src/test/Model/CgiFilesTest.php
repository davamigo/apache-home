<?php

namespace Test\Davamigo\ApacheHome\Model;

use Davamigo\ApacheHome\Model\CgiFiles;

/**
 * Test for CgiFiles Model
 *
 * @package Test\Davamigo\ApacheHome\Model
 * @author David Amigo <davamigo@gmail.com>
 * @group Test_Unit_Davamigo_ApacheHome_Model_CgiFiles
 * @group Test_Unit_Davamigo_ApacheHome_Model
 * @group Test_Unit_Davamigo_ApacheHome
 * @group Test_Unit_Davamigo
 * @group Test_Unit
 * @group Test
 */
class CgiFilesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic model test
     */
    public function testBasic()
    {
        // Configure the test case
        $model = new CgiFiles();

        // Assertions
        $this->assertInternalType('array', $model->getFiles());
        $this->assertInternalType('array', $model->getFolders());
        $this->assertInternalType('array', $model->getFilesAndFolders());
    }

    /**
     * Test validateFile method
     */
    public function testValidateFileWhenNotExecutable()
    {
        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\CgiFiles')
            ->disableOriginalConstructor()
            ->setMethods(array('realPath', 'isDir', 'isExecutable'))
            ->getMock();

        // Method realPath($basedir, $filename) will return the $filename
        $mock
            ->expects($this->exactly(2))
            ->method('realPath')
            ->willReturnArgument(1);

        // Method isDir($path) will return false
        $mock
            ->expects($this->once())
            ->method('isDir')
            ->willReturn(false);

        // Method isExecutable($path) will return false
        $mock
            ->expects($this->once())
            ->method('isExecutable')
            ->willReturn(false);

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\CgiFiles');
        $method = $reflection->getMethod('validateFile');
        $method->setAccessible(true);
        $result = $method->invoke($mock, 'dir', 'file', array());

        // Assertions
        $this->assertFalse($result);
    }

    /**
     * Test validateFile method
     */
    public function testValidateFileWhenExecutable()
    {
        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\CgiFiles')
            ->disableOriginalConstructor()
            ->setMethods(array('realPath', 'isDir', 'isExecutable'))
            ->getMock();

        // Method realPath($basedir, $filename) will return the $filename
        $mock
            ->expects($this->exactly(2))
            ->method('realPath')
            ->willReturnArgument(1);

        // Method isDir($path) will return false
        $mock
            ->expects($this->once())
            ->method('isDir')
            ->willReturn(false);

        // Method isExecutable($path) will return false
        $mock
            ->expects($this->once())
            ->method('isExecutable')
            ->willReturn(true);

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\CgiFiles');
        $method = $reflection->getMethod('validateFile');
        $method->setAccessible(true);
        $result = $method->invoke($mock, 'dir', 'file', array());

        // Assertions
        $this->assertTrue($result);
    }
}
