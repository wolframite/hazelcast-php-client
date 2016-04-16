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
