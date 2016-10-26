<?php

namespace Davamigo\ApacheHome\Model;

/**
 * Model to get the opened ports
 *
 * @package Davamigo\ApacheHome\Model
 * @author David Amigo <davamigo@gmail.com>
 */
class Ports
{
    /** @var array */
    protected $ports;

    /**
     * Ports constructor.
     */
    public function __construct()
    {
        $this->scanPorts();
        $this->sortPorts();
    }

    /**
     * Get the opened ports
     *
     * @return array
     */
    public function getPorts()
    {
        return $this->ports;
    }

    /**
     * Scan opend ports
     *
     * @return void
     */
    protected function scanPorts()
    {
        $this->ports = array();
        $result = $this->netstat();
        $list = explode("\n", $result);
        foreach ($list as $text) {
            $port = $this->readPort($text);
            if (null !== $port) {
                $this->ports[] = $port;
            }
        }
    }

    /**
     * Sorts the files array and the folders array
     *
     * @return void
     */
    protected function sortPorts()
    {
        sort($this->ports, SORT_NUMERIC);
    }

    /**
     * Execute netstat command to get the openned ports
     *
     * @return string
     * @codeCoverageIgnore
     */
    protected function netstat()
    {
        return @shell_exec('netstat -ntlp 2> /dev/null | grep ":::"');
    }

    /**
     * read port from a text line of netstat
     *
     * @param string $text
     * @return string|null
     */
    protected function readPort($text)
    {
        preg_match('/:::(\d+)/', $text, $matches);
        if (count($matches) > 1) {
            return $matches[1];
        }
        return null;
    }
}
