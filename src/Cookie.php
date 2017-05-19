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
     * Cookie Constructor Method
     *
     * @param string                 $name     The name of the cookie
     * @param string                 $value    The value of the cookie
     * @param int|\DateTimeInterface $expire   The expires time of the cookie
     * @param string                 $path     The path of the cookie
     * @param string                 $domain   The domain of the cookie
     * @param bool                   $secure   True when the cookie can be transmitted only via HTTPS
     * @param bool                   $httpOnly True if the cookie can be accessible only via HTTP protocol
     *
     * @throws \InvalidArgumentException If the Name is empty
     *                                   If the Value is null
     *                                   If the Expiration time is not valid
     */
    public function __construct($name, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $http_only = null)
    {
        // Check if the Name is valid
        if (empty($name)) {
            throw new \InvalidArgumentException('Cookie Error: The cookie name cannot be empty.');
        }

        if (is_null($value)) {
            throw new \InvalidArgumentException('Cookie Error: The cookie value cannot be empty.');
        }

        // DateTime Conversion
        switch (true) {
            case ($expire instanceof \DateTimeInterface) :
                $expire = $expire->format('U');
                break;
            case (is_null($expire)):
                $expire = 0;
                break;
            case (!is_numeric($expire)) :
                $expire = strtotime($expire);
        }
        if (false === $expire) {
            throw new \InvalidArgumentException('Cookie Error: Expiration time is not valid.');
        }

        // Values assignment
        $this->name     = $name;
        $this->value    = $value;
        $this->expire   = (int) $expire;
        $this->path     = !is_null($path) ? $path : '/';
        $this->domain   = $domain;
        $this->secure   = !is_null($secure) ? (bool) $secure : false;
        $this->httpOnly = !is_null($http_only) ? (bool) $http_only : true;
    }

    /**
     * Create a cookie object using values from a json
     *
     * @params string $cookie_json
     * @return Cookie
     */
    public static function createFromJSON($cookie_json)
    {
        $cookie_obj = json_decode($cookie_json);

        return new static(
            $cookie_obj->name,
            $cookie_obj->value,
            isset($cookie_obj->expire) ? $cookie_obj->expire : 0,
            isset($cookie_obj->path) ? $cookie_obj->path : '/',
            isset($cookie_obj->domain) ? $cookie_obj->domain : null,
            isset($cookie_obj->secure) ? $cookie_obj->secure : false,
            isset($cookie_obj->httpOnly) ? $cookie_obj->httpOnly : true
        );
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
     * Gets the JSON of the Cookie.
     *
     * @return string
     */
    public function getJSON()
    {
        $cookie_array = [
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'expire' => $this->getExpires(),
            'path' => $this->getPath(),
            'domain' => $this->getDomain(),
            'secure' => $this->isSecure(),
            'httpOnly' => $this->isHttpOnly()
        ];

        return json_encode($cookie_array);
    }
}