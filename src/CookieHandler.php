<?php

namespace Sqz\CookieHandler;

/**
 * This is the Cookie Handler class.
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 *
 * @copyright MIT License
 *
 * @license https://github.com/zeroastro/sqz-cookie-handler/blob/master/LICENSE
 */
class CookieHandler
{
    /**
     * Cryptography Class
     *
     * @var CryptographerInterface
     */
    protected $cryptographer;

    /**
     * CookieHandler constructor.
     *
     * @param CryptographerInterface|null $cryptographer
     */
    public function __construct(CryptographerInterface $cryptographer = null)
    {
        if ($cryptographer instanceof CryptographerInterface) {
            $this->cryptographer = $cryptographer;
        }
    }

    /**
     * This is a wrapper function for the native php setcookie() function.
     *
     * @param Cookie $cookie The cookie, represented as Cookie class
     *
     * @return bool
     */
    public function saveCookie(Cookie $cookie): bool
    {
        $value = !is_null($this->cryptographer)
            ? $this->cryptographer->encrypt($cookie->getJSON())
            : $cookie->getJSON();

        return setcookie(
            $cookie->getName(),
            $value,
            $cookie->getExpiration(),
            $cookie->getPath(),
            $cookie->isSecure(),
            $cookie->isHttpOnly()
        );
    }

    /**
     * Whether it exists, return the Cookie
     *
     * @param string $cookieName The name of the cookie to retrieve
     *
     * @return Cookie|null
     */
    public function getCookie($cookieName)
    {
        if (empty($_COOKIE[$cookieName])) {
            return null;
        }

        $cookieJSON = !is_null($this->cryptographer)
            ? $this->cryptographer->decrypt($_COOKIE[$cookieName])
            : $_COOKIE[$cookieName];

        return Cookie::createFromJSON($cookieJSON);
    }

    /**
     * Removes a cookie using the native php setcookie() function
     *
     * @param string $cookieName The name of the cookie to remove
     *
     * @return bool
     */
    public function removeCookie($cookieName): bool
    {
        if (isset($_COOKIE[$cookieName])) {
            unset($_COOKIE[$cookieName]);
            
            return setcookie($cookieName, 'deleted', 1, '/');
        }

        return false;
    }
}
