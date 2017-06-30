<?php
/** 
 * This is the Cookie Handler class.
 * This class uses OpenSSL to encrypt/decrypt the cookies value.
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 * @copyright MIT License
 * @license https://github.com/zeroastro/SQZ-Cookie-Handler/blob/master/LICENSE
 */
 
namespace SQZ_CookieHandler;

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
            try {
                $this->security = new SimpleSecurity($key);
            } catch (\Exception $e) {
                error_log('SQZ Cookie Handler Error: ' . $e->getMessage());
                printf('SQZ Cookie Handler Error: ' . $e->getMessage());
                $key = null;
            }
        }

        $this->key = $key;
    }

    /**
     * This is a wrapper function for the native php setcookie() function.
     *
     * @param Cookie $cookie The cookie, rapresented as SQZ_CookieHandler\Cookie class
     * @return bool
     */
    public function saveCookie(Cookie $cookie, $encrypt = false)
    {
        $value = ((true === $encrypt) && !empty($this->key))
            ? $this->security->encrypt($cookie->getJSON())
            : $cookie->getJSON();

        return \setcookie(
            $cookie->getName(),
            $value,
            $cookie->getExpires(),
            $cookie->getPath(),
            $cookie->isSecure(),
            $cookie->isHttpOnly()
        );
    }

    /**
     * Whether it exists, return the Cookie 
     *
     * @param string $cookieName The name of the cookie to retrieve
     * @return Cookie|null
     */
    public function getCookie($cookie_name, $decrypt = false)
    {
        if (empty($_COOKIE[$cookie_name])) {
            return null;
        }

        $cookie_json = ((true === $decrypt) && !empty($this->key))
            ? $this->security->decrypt($_COOKIE[$cookie_name])
            : $_COOKIE[$cookie_name];

        return Cookie::createFromJSON($cookie_json);
    }

    /**
     * Removes a cookie using the native php setcookie() function
     *
     * @param string $cookieName The name of the cookie to remove
     * @return bool
     */
    public function removeCookie($cookie_name)
    {
        return setcookie($cookie_name, 'deleted', 1, '/');
    }
}
