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
     * @var string
     */
    protected $packString;

    /**
     * @var array
     */
    protected $packValues;

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @var SerializerConfig
     */
    protected $config;

    /**
     * @var array
     */
    protected $packMap = [
        self::HZ_STRING => 'Va'
    ];

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
        $this->packString = '';
        $this->packValues = [];

        $reflectionClass = new \ReflectionClass(get_class($message));
        $properties = $reflectionClass->getProperties();
        $reader = $this->getReader();
        $headerMap = [];
        $messageMap = [];

        /** @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            /** @var HzType $type */
            $type = $reader->getPropertyAnnotation($property, static::TYPE_ANNOTATION);
            if (!empty($type)) {
                $position = $type->getPosition();
                if ($position < 0) {
                    $headerMap[abs($type->getPosition())] = [
                        'name' => $property->getName(),
                        'type' => $type->getType()
                    ];
                } else {
                    $messageMap[$type->getPosition()] = [
                        'name' => $property->getName(),
                        'type' => $type->getType()
                    ];
                }
            }
        }

        // Just to be safe
        ksort($headerMap);
        ksort($messageMap);

        $this->buildPackString($this->packString, $this->packValues, $headerMap, $message);
        $this->buildPackString($this->packString, $this->packValues, $messageMap, $message);
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

    /**
     * @param string $packString
     * @param array $packValues
     * @param array $map
     * @param Message $msg
     */
    protected function buildPackString(&$packString, array &$packValues, array $map, Message $msg)
    {
        foreach ($map as $item) {
            switch ($item['type']) {
                case static::HZ_STRING:
                    $packString .= $this->packMap[$item['type']];
                    $value = (string) call_user_func(array($msg, sprintf('get%s', ucfirst($item['name']))));

                    $packValues[] = strlen($value);
                    $packValues[] = $value;
            }
        }
    }
}
