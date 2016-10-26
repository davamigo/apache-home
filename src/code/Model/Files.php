<?php

namespace Davamigo\ApacheHome\Model;

/**
 * Model to get the files and folders
 *
 * @package Davamigo\ApacheHome\Model
 * @author David Amigo <davamigo@gmail.com>
 */
class Files
{
    /** @var array */
    protected $files;

    /** @var array */
    protected $folders;

    /**
     * Files constructor.
     *
     * @param string $basedir
     * @param array  $filesToIgnore
     * @param array  $foldersToIgnore
     */
    public function __construct(
        $basedir = null,
        array $filesToIgnore = array(),
        array $foldersToIgnore = array()
    ) {
        if (!$basedir) {
            $basedir = $_SERVER['DOCUMENT_ROOT'];
            if (!$basedir) {
                $basedir = __DIR__ . '/../../..';
            }
        }

        $this->scanFilesAndFolders($basedir, $filesToIgnore, $foldersToIgnore);
        $this->sortFilesAndFolders();
    }

    /**
     * Get files
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Get folders
     *
     * @return array
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * Get files & folders
     *
     * @return array
     */
    public function getFilesAndFolders()
    {
        return $this->files + $this->folders;
    }

    /**
     * Scan for development files and folders
     *
     * @param string $basedir
     * @param array  $filesToIgnore
     * @param array  $foldersToIgnore
     * @return void
     */
    protected function scanFilesAndFolders($basedir, array $filesToIgnore, array $foldersToIgnore)
    {
        $this->files = $this->folders = array();
        $resource = $this->openDir($basedir);
        if ($resource) {
            while (($filename = $this->readDir($resource)) !== false) {
                if ($this->validateFolder($basedir, $filename, $foldersToIgnore)) {
                    $this->folders[] = $filename;
                } elseif ($this->validateFile($basedir, $filename, $filesToIgnore)) {
                    $this->files[] = $filename;
                }
            }
        }
    }

    /**
     * Sorts the files array and the folders array
     *
     * @return void
     */
    protected function sortFilesAndFolders()
    {
        sort($this->files, SORT_STRING | SORT_FLAG_CASE);
        sort($this->folders, SORT_STRING | SORT_FLAG_CASE);
    }

    /**
     * Return if a folder is valid
     *
     * @param string $basedir
     * @param string $filename
     * @param array  $foldersToIgnore
     * @return bool
     */
    protected function validateFolder($basedir, $filename, array $foldersToIgnore)
    {
        $fullName = $this->realPath($basedir, $filename);
        if (false === $fullName) {
            return false;
        }

        return $this->isDir($fullName)
            && !$this->ignoreFileOrFolder($filename, $foldersToIgnore);
    }

    /**
     * Return if a file is valid
     *
     * @param string $basedir
     * @param string $filename
     * @param array  $filesToIgnore
     * @return bool
     */
    protected function validateFile($basedir, $filename, array $filesToIgnore)
    {
        $fullName = $this->realPath($basedir, $filename);
        if (false === $fullName) {
            return false;
        }

        return !$this->isDir($fullName)
            && !$this->ignoreFileOrFolder($filename, $filesToIgnore);
    }

    /**
     * Get if a file has to be ignored
     *
     * @param string $filename
     * @param array  $ignore
     * @return bool
     */
    protected function ignoreFileOrFolder($filename, array $ignore)
    {
        return false !== array_search($filename, $ignore);
    }

    /**
     * Open directory handle
     *
     * @param string $path
     * @return resource|false
     */
    protected function openDir($path)
    {
        return @opendir($path);
    }

    /**
     * Read entry from directory handle
     *
     * @param resource $resource
     * @return string|false
     */
    protected function readDir($resource)
    {
        return @readdir($resource);
    }

    /**
     * Returns canonicalized absolute pathname
     *
     * @param string $basedir
     * @param string $filename
     * @return string|false
     */
    protected function realPath($basedir, $filename)
    {
        return @realpath($basedir . '/' . $filename);
    }

    /**
     * Tells whether the filename is a directory
     *
     * @param string $filename
     * @return bool
     */
    protected function isDir($filename)
    {
        return @is_dir($filename);
    }
}
