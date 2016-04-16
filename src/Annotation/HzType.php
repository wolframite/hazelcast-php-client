<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Annotation;

/**
 * Doctrine annotation
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes(
 *     @Attribute("type", type = "string")
 * )
 * @package Hazelcast\Annotation
 */
class HzType
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->type = current($values);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
