<?php
/** 
 * This is the Cookie Handler class.
 * This class uses OpenSSL to encrypt/decrypt the cookies value.
 *
 * @author Salvatore Q Zeroastro <zeroastro@gmail.com>
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

    /* Encryption Method */
    const ENCRYPTION_METHOD = 'AES-256-CBC';

    /* Size of the Initial Vector */
    const IV_SIZE = 16;

    /**
     * The CookieHandler Constructor
     *
     * @param string $key The Encryption Key. If null, the cookies will not be encrypted.
     * @throws \RuntimeException If enabling Encryption without OpenSSL extension loaded
     */
    public function __construct($key = null)
    {
        if (!empty($key) && !extension_loaded('openssl')) {
            throw new \RuntimeException(sprintf(
                "You need OpenSSL extension installed in order to use %s",
                __CLASS__
            ));
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
            ? $this->encrypt($cookie->getValue())
            : $cookie->getValue();

        return setcookie(
            $cookie->getName(),
            $value,
            $cookie->getExpires(),
            $cookie->getPath(),
            $cookie->isSecure(),
            $cookie->isHttpOnly()
        );
    }

    /**
     * Whether it exists, return the value of the requested cookie 
     *
     * @param string $cookieName The name of the cookie to retrieve
     * @return string|null
     */
    public function getCookie($cookie_name, $decrypt = false)
    {
        if (empty($_COOKIE[$cookie_name])) {
            return false;
        }

        return ((true === $decrypt) && !empty($this->key))
            ? $this->decrypt($_COOKIE[$cookie_name])
            : $_COOKIE[$cookie_name];
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

    /**
     * Simple data Encryption using OpenSSL
     *
     * @param string $data The data to encrypt
     * @return string|false
     */
    protected function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes(self::IV_SIZE);

        $encrypted = openssl_encrypt(
            $data, 
            self::ENCRYPTION_METHOD, 
            $this->key, 
            OPENSSL_RAW_DATA, 
            $iv
        );
        
        return base64_encode($iv . $encrypted);
    }

    /**
     * Simple data Decryption using OpenSSL
     *
     * @param string $data The data to decrypt
     * @return string|false
     */
    protected function decrypt($data)
    {
        $decoded = base64_decode($data);
        $iv = substr($decoded, 0, self::IV_SIZE);
        $encrypted = substr($decoded, self::IV_SIZE);

        return openssl_decrypt(
            $encrypted,
            self::ENCRYPTION_METHOD, 
            $this->key, 
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}
