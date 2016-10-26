<?php

namespace Davamigo\ApacheHome\Model;

use Davamigo\ApacheHome\Entity\VirtualHost;

/**
 * Model to get the virtual hosts
 *
 * @package Davamigo\ApacheHome\Model
 * @author David Amigo <davamigo@gmail.com>
 */
class VirtualHosts
{
    /** @var VirtualHost[] */
    protected $virtualHosts;

    /**
     * VirtualHosts constructor.
     *
     * @param string $basedir
     * @param array  $ignore
     */
    public function __construct($basedir = null, array $ignore = array())
    {
        if (!$basedir) {
            $basedir = '/etc/apache2/sites-enabled/';
        }

        $this->scanVirtualHostsInFolder($basedir, $ignore);
        $this->sortVirtualHosts();
    }

    /**
     * Get virtual hosts list
     *
     * @return VirtualHost[]
     */
    public function getVirtualHosts()
    {
        return $this->virtualHosts;
    }

    /**
     * Scan for virtual hosts in al the files of a directory
     *
     * @param string $basedir
     * @param array  $ignore
     * @return void
     */
    protected function scanVirtualHostsInFolder($basedir, array $ignore)
    {
        $this->virtualHosts = array();
        $resource = $this->openDir($basedir);
        if ($resource) {
            while (($fileName = $this->readDir($resource)) !== false) {
                $fullName = $this->realPath($basedir, $fileName);
                if (!$this->isDir($fullName) && !$this->ignoreFile($fileName, $ignore)) {
                    $buffer = $this->fileGetContents($fullName);
                    if (!empty($buffer)) {
                        $this->scanVirtualHostsInBuffer($buffer, $fileName);
                    }
                }
            }
        }
    }

    /**
     * Sorts the virtual hosts array
     *
     * @return void
     */
    protected function sortVirtualHosts()
    {
        usort($this->virtualHosts, function (VirtualHost $a, VirtualHost $b) {
            return strcasecmp($a->getServerName(), $b->getServerName());
        });
    }

    /**
     * Find virtual hosts in a read buffer
     *
     * @param string $buffer
     * @param string $fileName
     * @return int
     */
    protected function scanVirtualHostsInBuffer($buffer, $fileName)
    {
        $count = 0;
        $start = stripos($buffer, '<VirtualHost');
        while ($start !== false) {
            $end = stripos($buffer, '</VirtualHost>', $start);
            if ($end == false) {
                break;
            }

            $serverName = $this->findValueInBuffer($buffer, 'ServerName', $start, $end, null);
            $documentRoot = $this->findValueInBuffer($buffer, 'DocumentRoot', $start, $end, null);
            $directoryIndex = $this->findValueInBuffer($buffer, 'DirectoryIndex', $start, $end, null);

            if (null != $serverName && null != $documentRoot) {
                $this->virtualHosts[] = new VirtualHost(
                    $fileName,
                    $serverName,
                    $documentRoot,
                    $directoryIndex
                );
                $count++;
            }

            $start = stripos($buffer, '<VirtualHost', $end + 14);
        }

        return $count;
    }

    /**
     * Find values in a buffer
     *
     * @param string $buffer
     * @param string $key
     * @param int    $start
     * @param int    $end
     * @param string $default
     * @return string
     */
    protected function findValueInBuffer($buffer, $key, $start, $end, $default = null)
    {
        $result = $default;

        $isLineStart = true;
        $isKeyword = false;
        $isComment = false;
        $keyword = "";
        $value = "";

        for ($pos = $start; $pos <= $end; ++$pos) {
            $char = $buffer[$pos];
            switch ($char) {
                case "\r":
                case "\n":
                    if ($keyword && $keyword == $key) {
                        $result = $value;
                    }
                    $isLineStart = true;
                    $isKeyword = false;
                    $isComment = false;
                    $keyword = "";
                    $value = "";
                    break;

                case " ":
                case "\t":
                    if (!$isComment && !$isLineStart) {
                        if ($isKeyword) {
                            $isKeyword = false;
                        } else {
                            $value .= $char;
                        }
                    }
                    break;

                case '#':
                    $isLineStart = false;
                    $isKeyword = false;
                    $isComment = true;
                    break;

                default:
                    if (!$isComment) {
                        if ($isLineStart) {
                            $isLineStart = false;
                            $isKeyword = true;
                        }
                        if ($isKeyword) {
                            if ($char == "<" || $char == ">"
                                || $char == "-" || $char == "_"
                                || ($char >= "a" && $char <= "z")
                                || ($char >= "A" && $char <= "Z")
                                || ($char >= "0" && $char <= "9")) {
                                $keyword .= $char;
                            } else {
                                $isKeyword = false;
                                $value = $keyword . $char;
                                $keyword = "";
                            }
                        } else {
                            $value .= $char;
                        }
                    }
            }
        }

        return trim($result);
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
     * Tells whether the filename is a directory
     *
     * @param string $filename
     * @return bool
     */
    protected function isDir($filename)
    {
        return @is_dir($filename);
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
     * Reads entire file into a string
     *
     * @param string $filename
     * @return string
     */
    protected function fileGetContents($filename)
    {
        return @file_get_contents($filename);
    }

    /**
     * Get if a file has to be ignored
     *
     * @param string $filename
     * @param array  $ignoreList
     * @return bool
     */
    protected function ignoreFile($filename, array $ignoreList)
    {
        return false !== array_search($filename, $ignoreList);
    }
}
