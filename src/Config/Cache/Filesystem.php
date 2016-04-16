<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Config\Cache;

/**
 * Configure Doctrine's filesystem cache
 * @package Hazelcast\Config\Cache
 */
class Filesystem extends Provider
{
    /**
     * @var string
     */
    protected $directory;

    /**
     * @param string $directory
     * Set default directory
     */
    public function __construct($directory = null)
    {
        parent::__construct();

        if (empty($directory)) {
            $directory = sys_get_temp_dir();
        }

        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return array
     */
    public function getConfigArray()
    {
        return [$this->directory];
    }
}
