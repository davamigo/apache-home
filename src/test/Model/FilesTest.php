<?php

namespace Test\Davamigo\ApacheHome\Model;

use Davamigo\ApacheHome\Model\Files;

/**
 * Test for Files Model
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
class FilesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic model test
     */
    public function testBasic()
    {
        // Configure the test case
        $model = new Files();

        // Assertions
        $this->assertInternalType('array', $model->getFiles());
        $this->assertInternalType('array', $model->getFolders());
        $this->assertInternalType('array', $model->getFilesAndFolders());
    }

    /**
     * Test scanFilesAndFolders method
     */
    public function testScanFilesAndFolders()
    {
        // Test data
        $data = array(
            // 'file_os_folder'   => is_dir?
            '.'                   => true,
            '..'                  => true,
            'some_dir'            => true,
            'some_dir_to_ignore'  => true,
            'some_file'           => false,
            'some_file_to_ignore' => false
        );

        $filesToIgnore = array(
            'some_file_to_ignore'
        );

        $foldersToIgnore = array(
            'some_dir_to_ignore'
        );

        $expectedFiles = array(
            'some_file'
        );

        $expectedFolders = array(
            '.',
            '..',
            'some_dir'
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Files')
            ->disableOriginalConstructor()
            ->setMethods(array('openDir', 'readDir', 'isDir', 'realPath'))
            ->getMock();

        /** @var Files $model */
        $model = $mock;

        // Method openDir() will return true once
        $mock
            ->expects($this->once())
            ->method('openDir')
            ->willReturn(true);

        // Method readDir() will return the current key of $data and will advance the pointer of $data.
        // If no more elements it will return false
        $mock
            ->expects($this->exactly(1 + count($data)))
            ->method('readDir')
            ->willReturnCallback(function () use (&$data) {
                if (null === ($key = key($data))) {
                    return false;
                }
                next($data);
                return $key;
            });

        // Method isDir($path) will return $data[$path]
        $mock
            ->expects($this->any())
            ->method('isDir')
            ->willReturnCallback(function ($path) use ($data) {
                return $data[$path];
            });

        // Method realPath($basedir, $filename) will return the $filename
        $mock
            ->expects($this->any())
            ->method('realPath')
            ->willReturnArgument(1);

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Files');
        $method = $reflection->getMethod('scanFilesAndFolders');
        $method->setAccessible(true);
        $method->invoke($mock, '.', $filesToIgnore, $foldersToIgnore);

        // Assertions
        $this->assertEquals($expectedFiles, $model->getFiles());
        $this->assertEquals($expectedFolders, $model->getFolders());
        $this->assertEquals($expectedFiles + $expectedFolders, $model->getFilesAndFolders());
    }

    /**
     * Test sortFilesAndFolders method
     */
    public function testSortFilesAndFolders()
    {
        // Test data
        $files = array(
            'File3',
            'file2',
            'FILE1',
            'fILE4'
        );

        $folders = array(
            'folder1',
            'fOlDeR3',
            'FoldeR4',
            'FOLDER2'
        );

        $expectedFiles = array(
            'FILE1',
            'file2',
            'File3',
            'fILE4',
        );

        $expectedFolders = array(
            'folder1',
            'FOLDER2',
            'fOlDeR3',
            'FoldeR4',
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Files')
            ->disableOriginalConstructor()
            ->getMock();

        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Files');
        $filesProperty = $reflection->getProperty('files');
        $filesProperty->setAccessible(true);
        $filesProperty->setValue($mock, $files);

        $foldersProperty = $reflection->getProperty('folders');
        $foldersProperty->setAccessible(true);
        $foldersProperty->setValue($mock, $folders);

        $method = $reflection->getMethod('sortFilesAndFolders');
        $method->setAccessible(true);
        $method->invoke($mock);

        // Assertions
        $this->assertEquals($expectedFiles, $filesProperty->getValue($mock));
        $this->assertEquals($expectedFolders, $foldersProperty->getValue($mock));
    }

    /**
     * Test validateFolder method
     */
    public function testValidateFolderWithInvalidPathWillReturnFalse()
    {
        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Files')
            ->disableOriginalConstructor()
            ->setMethods(array('realPath'))
            ->getMock();

        // Method realPath($basedir, $filename) will return false
        $mock
            ->expects($this->any())
            ->method('realPath')
            ->willReturn(false);

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Files');
        $method = $reflection->getMethod('validateFolder');
        $method->setAccessible(true);
        $result = $method->invoke($mock, 'a_folder', 'a_file', array());

        // Assertions
        $this->assertFalse($result);
    }

    /**
     * Test validateFile method
     */
    public function testValidateFileWithInvalidPathWillReturnFalse()
    {
        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Files')
            ->disableOriginalConstructor()
            ->setMethods(array('realPath'))
            ->getMock();

        // Method realPath($basedir, $filename) will return false
        $mock
            ->expects($this->any())
            ->method('realPath')
            ->willReturn(false);

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Files');
        $method = $reflection->getMethod('validateFile');
        $method->setAccessible(true);
        $result = $method->invoke($mock, 'a_folder', 'a_file', array());

        // Assertions
        $this->assertFalse($result);
    }

    /**
     * Test ignoreFileOrFolder method
     */
    public function testIgnoreFileOrFolderWhenIgnore()
    {
        // Test data
        $filename = 'some_file';

        $ignoreList = array(
            'file_1',
            'some_file',
            'file_n'
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Files')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Files');
        $method = $reflection->getMethod('ignoreFileOrFolder');
        $method->setAccessible(true);
        $result = $method->invoke($mock, $filename, $ignoreList);

        // Assertions
        $this->assertTrue($result);
    }

    /**
     * Test ignoreFileOrFolder method
     */
    public function testIgnoreFileOrFolderWhenNotIgnore()
    {
        // Test data
        $filename = 'some_file';

        $ignoreList = array(
            'file_1',
            'file_2',
            'file_n'
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Files')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Files');
        $method = $reflection->getMethod('ignoreFileOrFolder');
        $method->setAccessible(true);
        $result = $method->invoke($mock, $filename, $ignoreList);

        // Assertions
        $this->assertFalse($result);
    }

    /**
     * Test ignoreFileOrFolder method
     */
    public function testIgnoreFileOrFolderWhenNoFilename()
    {
        // Test data
        $filename = null;

        $ignoreList = array(
            'file_1',
            'file_2',
            'file_n'
        );

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Files')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Files');
        $method = $reflection->getMethod('ignoreFileOrFolder');
        $method->setAccessible(true);
        $result = $method->invoke($mock, $filename, $ignoreList);

        // Assertions
        $this->assertFalse($result);
    }

    /**
     * Test ignoreFileOrFolder method
     */
    public function testIgnoreFileOrFolderWhenNoFilenameNorArray()
    {
        // Test data
        $filename = null;

        $ignoreList = array();

        // Configure the test case
        $mock = $this
            ->getMockBuilder('Davamigo\ApacheHome\Model\Files')
            ->disableOriginalConstructor()
            ->getMock();

        // Execute the test
        $reflection = new \ReflectionClass('Davamigo\ApacheHome\Model\Files');
        $method = $reflection->getMethod('ignoreFileOrFolder');
        $method->setAccessible(true);
        $result = $method->invoke($mock, $filename, $ignoreList);

        // Assertions
        $this->assertFalse($result);
    }
}
