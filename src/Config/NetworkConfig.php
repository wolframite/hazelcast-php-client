<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Config;

/**
 * @package Hazelcast\Config
 */
final class NetworkConfig
{
    /**
     * @var string
     */
    const DEFAULT_HOSTNAME = 'localhost';

    /**
     * @var string
     */
    const DEFAULT_PORT = 5701;
    
    /**
     * @var string
     */
    private $hostname;

    /**
     * @var int
     */
    private $port;

    /**
     * @param null $hostname
     * @param null $port
     */
    public function __construct($hostname = null, $port = null)
    {
        if (empty($hostname)) {
            $this->setHostname(self::DEFAULT_HOSTNAME);
        }

        if (empty($port)) {
            $this->setPort(self::DEFAULT_PORT);
        }
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param string $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('tcp://%s:%d', $this->getHostname(), $this->getPort());
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return md5($this->__toString());
    }
}
