<?php
/**
 * @author Wolfram Huesken <woh@m18.io>
 * @license https://www.apache.org/licenses/LICENSE-2.0.html Apache 2.0
 */

namespace Hazelcast\Config;

/**
 * @package Hazelcast\Config
 */
final class GroupConfig
{
    /**
     * @var string
     */
    const DEFAULT_USERNAME = 'dev';

    /**
     * @var string
     */
    const DEFAULT_PASSWORD = 'dev-pass';
    
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * GroupConfig constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct($username = null, $password = null)
    {
        if (empty($username)) {
            $this->setUsername(self::DEFAULT_USERNAME);
        }

        if (empty($password)) {
            $this->setPassword(self::DEFAULT_PASSWORD);
        }
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return GroupConfig
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return GroupConfig
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}
