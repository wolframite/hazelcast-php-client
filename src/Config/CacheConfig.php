<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Config;

use Doctrine\Common\Cache\Cache;
use Hazelcast\Config\Cache\Provider;

/**
 * @package Hazelcast\Config
 */
class CacheConfig
{
    /**
     * @var string
     */
    protected $defaultCacheConfig = '\Hazelcast\Config\Cache\Filesystem';

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var Provider
     */
    protected $config;

    /**
     * @return Cache
     */
    public function getCache()
    {
        if (empty($this->cache)) {
            $cacheClass = $this->getConfig()->getCacheClass();
            $configArray = $this->getConfig()->getConfigArray();

            $this->cache = new $cacheClass(...$configArray);
        }

        return $this->cache;
    }

    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @return Provider
     */
    public function getConfig()
    {
        if (empty($this->config)) {
            $this->config = new $this->defaultCacheConfig();
        }

        return $this->config;
    }

    /**
     * @param Provider $config
     */
    public function setConfig(Provider $config)
    {
        $this->config = $config;
    }
}
