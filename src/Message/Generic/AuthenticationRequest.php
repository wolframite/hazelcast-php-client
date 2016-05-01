<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Message\Generic;

use Hazelcast\Message\Message;
use Hazelcast\Annotation\HzType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * First message to be sent to the server
 *
 * @method void setUsername(string $username)
 * @method string getUsername()
 *
 * @method void setPassword(string $password)
 * @method string getPassword()
 *
 * @method void setUuid(string $uuid)
 * @method string getUuid()
 *
 * @method void setOwnerUuid(string $ownerUuid)
 * @method string getOwnerUuid()
 *
 * @method void setIsOwnerConnection(bool $isOwnerConnection)
 * @method bool getIsOwnerConnection()
 *
 * @method void setclientType(string $clientType)
 * @method string getClientType()
 *
 * @method void setSerializationVersion (string $serializationVersion)
 * @method string getSerializationVersion()
 */
class AuthenticationRequest extends Message
{
    /**
     * @var int
     */
    const MESSAGE_TYPE = 0x0002;

    /**
     * @var string
     * @HzType(type = "string", position = 1)
     * @Assert\NotBlank()
     */
    protected $username = 'dev';

    /**
     * @var string
     * @HzType(type = "string", position = 2)
     * @Assert\NotBlank()
     */
    protected $password = 'dev-pass';

    /**
     * @var string
     * @HzType(type = "string", position = 3)
     * @Assert\NotNull()
     */
    protected $uuid = '';

    /**
     * @var string
     * @HzType(type = "string", position = 4)
     * @Assert\NotNull()
     */
    protected $ownerUuid = '';

    /**
     * @var bool
     * @HzType(type = "boolean", position = 5)
     * @Assert\NotNull()
     */
    protected $isOwnerConnection = true;

    /**
     * @var string
     * @HzType(type = "string", position = 6)
     * @Assert\NotBlank()
     */
    protected $clientType = 'PHP';

    /**
     * @var int
     * @HzType(type = "uint8", position = 7)
     * @Assert\GreaterThan(value = 0)
     */
    protected $serializationVersion = 1;
}
