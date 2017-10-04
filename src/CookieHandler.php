<?php
/**
 * This is the Cookie Handler class.
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 * @copyright MIT License
 * @license https://github.com/zeroastro/SQZ-Cookie-Handler/blob/master/LICENSE
 */
 
namespace Sqz\CookieHandler;

class CookieHandler
{
    /**
     * Encryption Key
     *
     * @var string
     */
    protected $key;

    /**
     * Encryption Class
     *
     * @var SimpleSecurity
     */
    protected $security;

    /**
     * The CookieHandler Constructor
     *
     * @param string $key The Encryption Key. If null, the security class won't be created
     */
    public function __construct($key = null)
    {
        if (!empty($key)) {
            $this->security = new SimpleSecurity($key);
            $this->key = $key;
        }
    }

    /**
     * This is a wrapper function for the native php setcookie() function.
     *
     * @param Cookie $cookie The cookie, rapresented as Cookie class
     * @return bool
     */
    public function saveCookie(Cookie $cookie)
    {
        return setcookie(
            $cookie->getName(),
            !empty($this->key) ? $this->security->encrypt($cookie->getJSON()) : $cookie->getJSON(),
            $cookie->getExpires(),
            $cookie->getPath(),
            $cookie->isSecure(),
            $cookie->isHttpOnly()
        );
    }

    /**
     * Whether it exists, return the Cookie
     *
     * @param string $cookie_name The name of the cookie to retrieve
     * @return Cookie|null
     */
    public function getCookie($cookie_name)
    {
        if (empty($_COOKIE[$cookie_name])) {
            return null;
        }

        $cookie_json = !empty($this->key) ?
            $this->security->decrypt($_COOKIE[$cookie_name]) :
            $_COOKIE[$cookie_name];

        return Cookie::createFromJSON($cookie_json);
    }

    /**
     * Removes a cookie using the native php setcookie() function
     *
     * @param string $cookie_name The name of the cookie to remove
     * @return bool
     */
    public function removeCookie($cookie_name)
    {
        if (isset($_COOKIE[$cookie_name])) {
            unset($_COOKIE[$cookie_name]);
            
            return setcookie($cookie_name, 'deleted', 1, '/');
        }

        return false;
    }
}
