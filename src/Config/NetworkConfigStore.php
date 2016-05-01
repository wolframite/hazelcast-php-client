<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Config;

/**
 * Store NetworkConfig objects
 * @package Hazelcast\Config
 */
class NetworkConfigStore extends \SplObjectStorage
{
    /**
     * @param NetworkConfig $object
     * @param string $data
     */
    public function attach($object, $data = null)
    {
        if (!($object instanceof NetworkConfig)) {
            throw new \InvalidArgumentException('The NetworkConfigStore only accepts NetworkConfig objects');
        }

        if (empty($data)) {
            $data = $object->__toString();
        }

        parent::attach($object, $data);
    }

    /**
     * @param NetworkConfigStore $storage
     */
    public function addAll($storage)
    {
        if (!($storage instanceof NetworkConfigStore)) {
            throw new \InvalidArgumentException('addAll expects a NetworkConfigStore');
        }

        parent::addAll($storage);
    }

    /**
     * @return NetworkConfig[]
     */
    public function getNetworkConfigs() {
        $configs = iterator_to_array($this);

        if (empty($configs)) {
            $configs[] = new NetworkConfig();
        }

        return $configs;
    }
}
