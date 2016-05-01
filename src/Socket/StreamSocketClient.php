<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Socket;

use Hazelcast\Config\NetworkConfig;
use Hazelcast\Config\NetworkConfigStore;

/**
 * @package Hazelcast\Socket
 */
class StreamSocketClient
{
    /**
     * @var string
     */
    const CLIENT_BINARY_NEW = 'CB2';

    /**
     * @var resource
     */
    protected $client;

    /**
     * @var NetworkConfigStore
     */
    protected $config;

    /**
     * @param NetworkConfigStore $config
     */
    public function __construct(NetworkConfigStore $config)
    {
        $this->config = $config;
        $this->connect($config->getNetworkConfigs()[0]);
    }

    public function getSocket()
    {
        return $this->client;
    }

    /**
     * Create client and init connection
     * @param NetworkConfig $cfg
     */
    protected function connect(NetworkConfig $cfg)
    {
        $errorNumber = 0;
        $errorMessage = '';

        $client = stream_socket_client($cfg->__toString(), $errorNumber, $errorMessage);
        if ($client === false) {
            throw new \RuntimeException($errorMessage, $errorNumber);
        }

        fwrite($client, static::CLIENT_BINARY_NEW);
        $this->client = $client;
    }
}
