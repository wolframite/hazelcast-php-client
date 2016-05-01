<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Annotation;

use Zalora\Punyan\ZLog;

/**
 * Doctrine annotation
 *
 * @Annotation
 * @Target("PROPERTY")
 * @Attributes({
 *     @Attribute("type", type = "string"),
 *     @Attribute("position", type = "integer")
 * })
 * @package Hazelcast\Annotation
 */
class HzType
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $position;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        ZLog::trace('', $values);
        $this->type = $values['type'];
        $this->position = $values['position'];
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
