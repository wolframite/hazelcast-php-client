<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Message;

use Hazelcast\Annotation\HzType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Basic message, cares about header and offers functions to add data
 * @package Hazelcast\Message
 */
abstract class Message
{
    /**
     * Must be defined in the child class
     * @var int
     */
    const MESSAGE_TYPE = 0x0000;

    /**
     * @var int
     * @HzType(type = "int32", position = -1)
     * @Assert\GreaterThanOrEqual(value = 22)
     */
    protected $frameSize = 0;

    /**
     * @var int
     * @HzType(type = "uint8", position = -2)
     * @Assert\GreaterThan(value = 0)
     */
    protected $version = 1;

    /**
     * @var int
     * @HzType(type = "uint8", position = -3)
     * @Assert\GreaterThan(value = 0)
     */
    protected $flags = 0;

    /**
     * @var int
     * @HzType(type = "uint16", position = -4)
     * @Assert\GreaterThan(value = 0)
     */
    protected $type = 0;

    /**
     * @var int
     * @HzType(type = "int64", position = -5)
     * @Assert\NotIdenticalTo(value = 0)
     */
    protected $correlationId = 0;

    /**
     * @var int
     * @HzType(type = "int32", position = -6)
     * @Assert\NotIdenticalTo(value = 0)
     */
    protected $partitionId = -1;

    /**
     * @var int
     * @HzType(type = "uint16", position = -7)
     * @Assert\GreaterThanOrEqual(value = 22)
     */
    protected $dataOffset = 22;

    /**
     * Set message type automatically if it exists
     */
    public function __construct()
    {
        if (static::MESSAGE_TYPE > 0) {
            $this->type = static::MESSAGE_TYPE;
        }
    }

    /**
     * Automatic getters and setters for child classes
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (strlen($name) < 4) {
            throw new \BadMethodCallException('Message classes only have getters and setters');
        }

        $type = substr($name, 0, 3);
        $property = lcfirst(substr($name, 3));

        if (!in_array($type, ['get', 'set'])) {
            throw new \BadMethodCallException('Message classes only have getters and setters');
        }

        if (!property_exists($this, $property)) {
            $msg = sprintf('Class %s does not have the property %s', get_called_class(), $property);
            throw new \BadMethodCallException($msg);
        }

        if ($type === 'set' && empty($arguments)) {
            throw new \BadMethodCallException('Setters should set something, right?');
        }

        if ($type === 'get') {
            return $this->$property;
        }

        $this->$property = $arguments[0];
    }
}
