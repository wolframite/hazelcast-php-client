<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Config\Cache;
use Doctrine\Common\Cache\FilesystemCache;

/**
 * Grouping Interface
 * @package Hazelcast\Config\Cache
 */
abstract class Provider
{
    /**
     * @var string
     */
    const DOCTRINE_CACHE_CLASS_STUB = '\Doctrine\Common\Cache\%sCache';

    /**
     * @var string
     */
    protected $cacheClass;

    /**
     * return array
     */
    public abstract function getConfigArray();

    /**
     * Set cache class
     */
    public function __construct()
    {
        $calledClassParts = explode("\\", get_called_class());
        $calledClass = array_pop($calledClassParts);

        $this->cacheClass = sprintf(static::DOCTRINE_CACHE_CLASS_STUB, $calledClass);
    }

    /**
     * @return string
     */
    public function getCacheClass()
    {
        return $this->cacheClass;
    }
}
