<?php

namespace Sqz\CookieHandler;

use InvalidArgumentException;

/**
 * This Class represents a cookie
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 *
 * @copyright MIT License
 *
 * @license https://github.com/zeroastro/sqz-cookie-handler/blob/master/LICENSE
 */
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
     * The expiration time of the cookie
     *
     * @var int
     */
    protected $expiration;

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
     * @param string $name    The name of the cookie
     * @param string $value   The value of the cookie
     * @param int $expiration The expire time of the cookie
     * @param string $path    The path of the cookie
     * @param string $domain  The domain of the cookie
     * @param bool $secure    True when the cookie can be transmitted only via HTTPS
     * @param bool $httpOnly  True if the cookie can be accessible only via HTTP protocol
     */
    public function __construct(
        string $name,
        string $value,
        int $expiration = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = true
    ) {
        $this
            ->setName($name)
            ->setValue($value)
            ->setExpiration($expiration)
            ->setPath($path)
            ->setDomain($domain)
            ->setSecure($secure)
            ->setHttpOnly($httpOnly);
    }

    /**
     * Create a cookie object using values from a json
     *
     * @param string $cookieJson
     *
     * @return Cookie
     */
    public static function createFromJSON(string $cookieJson)
    {
        $cookieObject = json_decode($cookieJson);

        return new static(
            $cookieObject->name,
            $cookieObject->value,
            $cookieObject->expiration ?? 0,
            $cookieObject->path ?? '/',
            $cookieObject->domain ?? '',
            $cookieObject->secure ?? false,
            $cookieObject->httpOnly ?? true
        );
    }

    /**
     * Set the name of the cookie
     *
     * @param string $name
     *
     * @return Cookie
     *
     * @throws InvalidArgumentException
     */
    public function setName(string $name)
    {
        if (empty($name)) {
            throw new InvalidArgumentException('Cookie Error: The cookie name needs to be a valid string');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Returns the name of the cookie.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of the cookie
     *
     * @param string $value
     *
     * @return Cookie
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }


    /**
     * Returns the value of the cookie
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the expiration time of the cookie
     *
     * @param int $expiration
     *
     * @return Cookie
     */
    public function setExpiration(int $expiration)
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * Returns the Unix Timestamp of the Expiration time
     *
     * @return int
     */
    public function getExpiration(): int
    {
        return $this->expiration;
    }

    /**
     * Set the path of the cookie
     *
     * @param string $path
     *
     * @return Cookie
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Returns the path of the cookie
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set the domain of the cookie
     *
     * @param string $domain
     *
     * @return Cookie
     */
    public function setDomain(string $domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Returns the domain of the cookie
     *
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Set the secure flag of the cookie.
     *
     * @param bool $secure
     *
     * @return Cookie
     */
    public function setSecure(bool $secure)
    {
        $this->secure = $secure;

        return $this;
    }

    /**
     * Whether this cookie is accessible only via HTTPS or not
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * Set the http only availability of the cookie
     *
     * @param bool $httpOnly
     *
     * @return Cookie
     */
    public function setHttpOnly(bool $httpOnly)
    {
        $this->httpOnly = $httpOnly;

        return $this;
    }

    /**
     * Whether this cookie is accessible only via HTTP protocol or not
     *
     * @return bool
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * Whether this cookie is expired or not
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expiration < time();
    }

    /**
     * Returns the JSON representing the Cookie.
     *
     * @return string
     */
    public function getJSON(): string
    {
        return json_encode([
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'expiration' => $this->getExpiration(),
            'path' => $this->getPath(),
            'domain' => $this->getDomain(),
            'secure' => $this->isSecure(),
            'httpOnly' => $this->isHttpOnly()
        ]);
    }
}
