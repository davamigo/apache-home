<?php

namespace Davamigo\ApacheHome\Entity;

/**
 * Virtual Host Entity
 *
 * @package Davamigo\ApacheHome\Entity
 * @author David Amigo <davamigo@gmail.com>
 */
class VirtualHost
{
    /** @var string */
    private $fileName = null;

    /** @var string */
    private $serverName = null;

    /** @var string */
    private $documentRoot = null;

    /** @var string */
    private $directoryIndex = null;

    /**
     * VirtualHost constructor.
     *
     * @param string $fileName
     * @param string $serverName
     * @param string $documentRoot
     * @param string $directoryIndex
     */
    public function __construct($fileName = null, $serverName = null, $documentRoot = null, $directoryIndex = null)
    {
        $this->fileName = $fileName;
        $this->serverName = $serverName;
        $this->documentRoot = $documentRoot;
        $this->directoryIndex = $directoryIndex;
    }

    /**
     * Get file name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set file name
     *
     * @param string $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Get server name
     *
     * @return string
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * Set server name
     *
     * @param string $serverName
     * @return $this
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;
        return $this;
    }

    /**
     * Get document root
     *
     * @return string
     */
    public function getDocumentRoot()
    {
        return $this->documentRoot;
    }

    /**
     * Set document root
     *
     * @param string $documentRoot
     * @return $this
     */
    public function setDocumentRoot($documentRoot)
    {
        $this->documentRoot = $documentRoot;
        return $this;
    }

    /**
     * Get directory index
     *
     * @return string
     */
    public function getDirectoryIndex()
    {
        return $this->directoryIndex;
    }

    /**
     * Set directory index
     *
     * @param string $directoryIndex
     * @return $this
     */
    public function setDirectoryIndex($directoryIndex)
    {
        $this->directoryIndex = $directoryIndex;
        return $this;
    }
}
