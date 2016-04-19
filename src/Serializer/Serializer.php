<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Serializer;
use Hazelcast\Message\Message;

/**
 * @package Hazelcast\Serializer
 */
interface Serializer
{
    /**
     * @var string
     */
    const HZ_UINT8 = 'uint8';

    /**
     * @var string
     */
    const HZ_UINT16 = 'uint16';

    /**
     * @var string
     */
    const HZ_UINT32 = 'uint32';

    /**
     * @var string
     */
    const HZ_UINT64 = 'uint64';

    /**
     * @var string
     */
    const HZ_IN8 = 'int8';

    /**
     * @var string
     */
    const HZ_INT16 = 'int16';

    /**
     * @var string
     */
    const HZ_INT32 = 'int32';

    /**
     * @var string
     */
    const HZ_INT64 = 'int64';

    /**
     * @var string
     */
    const HZ_FLOAT = 'float';

    /**
     * @var string
     */
    const HZ_DOUBLE = 'double';

    /**
     * @var string
     */
    const HZ_BOOLEAN = 'boolean';

    /**
     * @var string
     */
    const HZ_STRING = 'string';

    /**
     * @var string
     */
    const HZ_BYTE_ARRAY = 'byte-array';

    /**
     * @param Message $message
     * @return string
     */
    public function serialize(Message $message);

    /**
     * @param string $binaryString
     * @return Message
     */
    public function unserialize($binaryString);
}
