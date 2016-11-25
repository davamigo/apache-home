<?php

namespace Davamigo\ApacheHome\Model;

use Davamigo\ApacheHome\Exception\ReadConfigException;
use Symfony\Component\Yaml\Yaml;

/**
 * Model to read the configuration file
 *
 * @package Davamigo\ApacheHome\Model
 * @author David Amigo <davamigo@gmail.com>
 */
class ReadConfig
{
    /** @var array */
    private $config = array(
        'document_root'         => null,
        'ignore_files'          => array( '.htaccess' ),
        'ignore_folders'        => array( '..', '.' ),
        'cgi_bin_folder'        => null,
        'cgi_bin_path'          => null,
        'cgi_ignore_files'      => array(),
        'cgi_ignore_folders'    => array( '..', '.' ),
        'request_scheme'        => null,
        'http_host'             => null
    );


    /**
     * ReadConfig constructor.
     *
     * @param string $file
     */
    public function __construct($file = 'src/config/config.yaml')
    {
        $this->readFile($file);
    }

    /**
     * @return string
     */
    public function getDocumentRoot()
    {
        $path = $this->config['document_root'];
        if (!$path) {
            $path = $_SERVER['DOCUMENT_ROOT'];
            if (!$path) {
                $path = '/var/www/html';
            }
        }

        return realpath($path);
    }

    /**
     * @return array
     */
    public function getIgnoreFiles()
    {
        return $this->config['ignore_files'];
    }

    /**
     * @return array
     */
    public function getIgnoreFolders()
    {
        return $this->config['ignore_folders'];
    }

    /**
     * @return string
     */
    public function getCgiBinFolder()
    {
        $folder = $this->config['cgi_bin_folder'];
        if (!$folder) {
            $folder = $_SERVER['HTTP_HOST'];
            if (!$folder) {
                $folder = 'cgi-bin';
            }
        }

        return $folder;
    }

    /**
     * @return string
     */
    public function getCgiBinPath()
    {
        $path = $this->config['cgi_bin_path'];
        if (!$path) {
            $path = $this->getDocumentRoot() . '/' . $this->getCgiBinFolder();
        }

        return realpath($path);
    }

    /**
     * @return array
     */
    public function getCgiIgnoreFiles()
    {
        return $this->config['cgi_ignore_files'];
    }

    /**
     * @return array
     */
    public function getCgiIgnoreFolders()
    {
        return $this->config['cgi_ignore_folders'];
    }

    /**
     * @return string
     */
    public function getRequestSchema()
    {
        $scheme = $this->config['request_scheme'];
        if (!$scheme) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
            if (!$scheme) {
                $scheme = 'http';
            }
        }

        return $scheme;

    }

    /**
     * @return string
     */
    public function getHttpHost()
    {
        $host = $this->config['http_host'];
        if (!$host) {
            $host = $_SERVER['HTTP_HOST'];
            if (!$host) {
                $host = 'localhost';
            }
        }

        return $host;
    }

    /**
     * Reads the config file
     *
     * @param string $file
     * @return void
     * @throws ReadConfigException
     */
    private function readFile($file)
    {
        $content = $this->getFileContents($file);
        if (!$content) {
            throw new ReadConfigException('File ' . $file . ' does not exist!');
        }

        try {
            $config = $this->parseYaml($content);
        } catch (\Exception $exc) {
            throw new ReadConfigException('An error occurred parsing the file ' . $file, $exc->getCode(), $exc);
        }

        if (array_key_exists('parameters', $config)) {
            $params = $config['parameters'];

            foreach (array_keys($this->config) as $key) {
                if (array_key_exists($key, $params) && $params[$key] !== null) {
                    if (!is_array($this->config[$key])) {
                        $this->config[$key] = $params[$key];
                    } else {
                        $this->config[$key] = array_merge(
                            $this->config[$key],
                            $params[$key]
                        );
                    }
                }
            }
        }
    }

    /**
     * Proxy function to allow testing
     *
     * @param string $file
     * @return string
     */
    private function getFileContents($file)
    {
        return @file_get_contents($file);
    }

    /**
     * Proxy function to allow testing
     *
     * @param string $content
     * @return array
     */
    private function parseYaml($content)
    {
        return Yaml::parse($content);
    }
}
