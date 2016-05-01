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
use Zalora\Punyan\ZLog;

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
     * @var int
     */
    protected $frameSize;

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
        self::HZ_STRING => 'Va',
        self::HZ_BOOLEAN => 'C',
        self::HZ_UINT8 => 'C',
        self::HZ_UINT16 => 'v',
        self::HZ_UINT32 => 'V',
        self::HZ_INT32 => 'l',
        self::HZ_INT64 => 'q',
    ];

    /**
     * @var array
     */
    protected $frameSizeMap = [
        self::HZ_STRING => 4,
        self::HZ_BOOLEAN => 1,
        self::HZ_UINT8 => 1,
        self::HZ_UINT16 => 2,
        self::HZ_UINT32 => 4,
        self::HZ_INT32 => 4,
        self::HZ_INT64 => 8
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
        $this->frameSize = 0;

        $reflectionClass = new \ReflectionClass(get_class($message));
        $properties = $reflectionClass->getProperties();
        $reader = $this->getReader();
        $headerMap = [];
        $messageMap = [];

        /** @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            ZLog::trace('', [
                'property' => $property->getName(),
                'phpDoc' => $property->getDocComment()
            ]);

            /** @var HzType $type */
            $type = $reader->getPropertyAnnotation($property, static::TYPE_ANNOTATION);
            if (!empty($type)) {
                $position = $type->getPosition();

                if (empty($position)) {
                    throw new \RuntimeException(sprintf("'%s' does not carry position", $property->getName()));
                }

                ZLog::trace('', [
                    'type' => $type->getType(),
                    'property' => $property->getName(),
                    'position' => $position
                ]);

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

        ZLog::trace('', ['header map' => $headerMap, 'message map' => $messageMap]);

        $this->buildPackString($this->packString, $this->packValues, $this->frameSize, $headerMap, $message);
        $this->buildPackString($this->packString, $this->packValues, $this->frameSize, $messageMap, $message);

        // Update frame size
        $this->packValues[0] = $this->frameSize;

        ZLog::trace('', [
            'packString' => $this->packString,
            'packValues' => $this->packValues
        ]);

        return pack($this->packString, ...$this->packValues);
    }

    /**
     * @param string $binaryString
     * @return Message
     */
    public function unserialize($binaryString)
    {
        echo "Do the magic: " . $binaryString;
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
     * @param int $frameSize
     * @param array $map
     * @param Message $msg
     */
    protected function buildPackString(&$packString, array &$packValues, &$frameSize, array $map, Message $msg)
    {
        foreach ($map as $item) {
            $frameSize += $this->frameSizeMap[$item['type']];

            switch ($item['type']) {
                case static::HZ_STRING:
                    $value = (string) call_user_func(array($msg, sprintf('get%s', ucfirst($item['name']))));
                    $dataLength = strlen($value);

                    $packValues[] = $dataLength;
                    $packValues[] = $value;

                    $packString .= $this->packMap[$item['type']];

                    $frameSize++;
                    if ($dataLength > 1) {
                        $packString .= $dataLength;
                        $frameSize += $dataLength - 1;
                    }

                    break;
                case static::HZ_UINT8:
                case static::HZ_UINT16:
                case static::HZ_UINT32:
                case static::HZ_UINT64:
                case static::HZ_INT8:
                case static::HZ_INT16:
                case static::HZ_INT32:
                case static::HZ_INT64:
                case static::HZ_BOOLEAN:
                    $packString .= $this->packMap[$item['type']];
                    $value = (int) call_user_func([$msg, sprintf('get%s', ucfirst($item['name']))]);
                    $packValues[] = $value;
                    break;

                case static::HZ_FLOAT:
                case static::HZ_DOUBLE:
                    $packString .= $this->packMap[$item['type']];
                    $value = (float) call_user_func([$msg, sprintf('get%s', ucfirst($item['name']))]);
                    $packValues[] = $value;
                    break;

                default:
                    echo "Unknown Type: " . $this->packMap[$item['type']];
            }
        }
    }
}
