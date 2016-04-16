<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Serializer;

use Hazelcast\Message\Message;
use Hazelcast\Annotation\HzType;
use Hazelcast\Config\SerializerConfig;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Converts data to binary string via pack()
 * @package Hazelcast\Serializer
 */
class DefaultSerializer implements Serializer
{
    /**
     * @var string
     */
    const TYPE_ANNOTATION = 'Hazelcast\Annotation\HzType';

    /**
     * @var array
     */
    protected $map;

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var SerializerConfig
     */
    protected $config;

    /**
     * @param SerializerConfig $serializerConfig
     */
    public function __construct(SerializerConfig $serializerConfig)
    {
        $this->config = $serializerConfig;
    }

    /**
     * @param Message $message
     * @return string
     */
    public function serialize(Message $message)
    {
        $reflectionClass = new \ReflectionClass(get_class($message));
        $properties = $reflectionClass->getProperties();

        /** @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            /** @var HzType $type */
            $type = $this->getReader()->getPropertyAnnotation($property, static::TYPE_ANNOTATION);
            if (!empty($type)) {
                $this->map[$property->getName()] = $type->getType();
            }
        }
    }

    public function unserialize($binaryString)
    {
        // TODO: Implement unserialize() method.
    }

    /**
     * @return Reader
     */
    protected function getReader()
    {
        if (empty($this->reader)) {
            $cache = $this->config->getCacheConfig()->getCache();
            if (!empty($cache)) {
                $reader = new CachedReader(new AnnotationReader(), $cache);
            } else {
                $reader = new AnnotationReader();
            }

            $this->reader = $reader;
        }

        return $this->reader;
    }
}
