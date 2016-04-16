<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Config;

/**
 * @package Hazelcast\Config
 */
final class SerializerConfig
{
    /**
     * @var CacheConfig
     */
    private $cacheConfig;

    /**
     * SerializerConfig constructor.
     * @param CacheConfig $cacheConfig
     */
    public function __construct(CacheConfig $cacheConfig)
    {
        $this->cacheConfig = $cacheConfig;
    }

    /**
     * @return CacheConfig
     */
    public function getCacheConfig()
    {
        return $this->cacheConfig;
    }

    /**
     * @param CacheConfig $cacheConfig
     */
    public function setCacheConfig($cacheConfig)
    {
        $this->cacheConfig = $cacheConfig;
    }
}
