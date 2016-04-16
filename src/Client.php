<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast;

use Hazelcast\Config\Config;
use Hazelcast\Serializer\DefaultSerializer;
use Hazelcast\Serializer\Serializer;

/**
 * Hazelcast client
 * @package Hazelcast
 */
class Client
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Do the magic
     */
    public function connect()
    {
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        if (empty($this->serializer)) {
            $this->serializer = new DefaultSerializer($this->config->getSerializerConfig());
        }

        return $this->serializer;
    }

    /**
     * @param Serializer $serializer
     */
    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }
}
