<?php

namespace Davamigo\ApacheHome\Model;

/**
 * Model to get the files and folders of the CGI folder
 *
 * @package Davamigo\ApacheHome\Model
 * @author David Amigo <davamigo@gmail.com>
 */
class CgiFiles extends Files
{
    /**
     * Files constructor.
     *
     * @param string $basedir
     * @param array  $filesToIgnore
     * @param array  $foldersToIgnore
     */
    public function __construct($basedir = null, array $filesToIgnore = array(), array $foldersToIgnore = array())
    {
        if (!$basedir) {
            $basedir = '/var/www/cgi-bin/';
        }

        parent::__construct($basedir, $filesToIgnore, $foldersToIgnore);
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
        return parent::validateFile($basedir, $filename, $filesToIgnore)
            && $this->isExecutable($this->realPath($basedir, $filename));
    }

    /**
     * Return if a file is file is executable
     *
     * @param $filename
     * @return bool
     */
    protected function isExecutable($filename)
    {
        return @is_executable($filename);
    }
}
