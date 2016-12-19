<?php
/** 
 * This Class represents a cookie.
 *
 * @author Salvatore Q Zeroastro <zeroastro@gmail.com>
 * @copyright MIT License
 * @license https://github.com/zeroastro/SQZ-Cookie-Handler/blob/master/LICENSE
 */
 
namespace SQZ_CookieHandler;

class Cookie
{
    /**
     * The name of the cookie
     *
     * @var string
     */
    protected $name;

    /**
     * The value of the cookie
     *
     * @var string
     */
    protected $value;

    /**
     * The expire time of the cookie
     *
     * @var string
     */
    protected $expire;

    /**
     * The path of the cookie
     *
     * @var string
     */
    protected $path;

    /**
     * The domain of the cookie
     *
     * @var string
     */
    protected $domain;

    /**
     * Boolean value for Security field
     *
     * @var bool
     */
    protected $secure;

    /**
     * Boolean value for HTTP Only field
     *
     * @var bool
     */
    protected $httpOnly;

    /**
     * The cookie name
     *
     * @var string
     */
    protected $sameSite;

    /*
     * SameSite valid values
     * null | lax | strict
     */
    const SAMESITE_LAX      = 'lax';
    const SAMESITE_STRICT   = 'strict';

    /**
     * Cookie Constructor Method
     *
     * @param string                 $name     The name of the cookie
     * @param string                 $value    The value of the cookie
     * @param int|\DateTimeInterface $expire   The expires time of the cookie
     * @param string                 $path     The path of the cookie
     * @param string                 $domain   The domain of the cookie
     * @param bool                   $secure   True when the cookie can be transmitted only via HTTPS
     * @param bool                   $httpOnly True if the cookie can be accessible only via HTTP protocol
     * @param string|null            $sameSite The SameSite value (null | lax | strict)
     *
     * @throws \InvalidArgumentException If the Name is empty
     *                                   If the Expiration time is not valid
     *                                   If the SameSite parameter is not valid
     */
    public function __construct($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $http_only = true, $same_site = null)
    {
        // Check if the Name is valid
        if (empty($name)) {
            throw new \InvalidArgumentException('Cookie Error: The cookie name cannot be empty.');
        }

        // DateTime Conversion
        switch (true) {
            case ($expire instanceof \DateTimeInterface) :
                $expire = $expire->format('U');
                break;
            case (!is_numeric($expire)) :
                $expire = strtotime($expire);
                break;
        }
        if (is_null($expire) || (false === $expire)) {
            throw new \InvalidArgumentException('Cookie Error: Expiration time is not valid.');
        }

        // Check if SameSite value is valid
        if (!(self::SAMESITE_LAX === $same_site || self::SAMESITE_STRICT === $same_site || is_null($same_site))) {
            throw new \InvalidArgumentException('The "SameSite" parameter value is not valid.');
        }

        // Values assignment
        $this->name     = $name;
        $this->value    = $value;
        $this->expire   = (int) $expire;
        $this->path     = $path;
        $this->domain   = $domain;
        $this->secure   = (bool) $secure;
        $this->httpOnly = (bool) $http_only;
        $this->sameSite = $same_site;
    }

    /**
     * Returns the name of the cookie.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of the cookie
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the Unix Timestamp of the Expiration time
     *
     * @return int
     */
    public function getExpires()
    {
        return (int) $this->expire;
    }

    /**
     * Returns the path of the cookie
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the domain of the cookie
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Whether this cookie is accessible only via HTTPS or not
     *
     * @return bool
     */
    public function isSecure()
    {
        return (bool) $this->secure;
    }

    /**
     * Whether this cookie is accessible only via HTTP protocol or not
     *
     * @return bool
     */
    public function isHttpOnly()
    {
        return (bool) $this->httpOnly;
    }

    /**
     * Whether this cookie is expired or not
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expire < time();
    }

    /**
     * Gets the SameSite attribute.
     *
     * @return string|null
     */
    public function getSameSite()
    {
        return $this->sameSite;
    }
}