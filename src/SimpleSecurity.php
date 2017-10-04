<?php
/** 
 * Utility class to perform simple data encryption/decryption
 * This class has been modified for the current project
 *
 * Gist URL: https://gist.github.com/zeroastro/14d8fa0d8f119bcaa63207216c4383a3
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 * @copyright MIT License
 */

namespace Sqz\CookieHandler;

class SimpleSecurity
{
    /**
     * Encryption Key 
     *
     * @var string 
     */
    protected $key;

    /**
     * Ininital Vector Size
     *
     * @var int
     */
    protected $iv_size;

    /** 
     * Encryption Method
     *
     * @var string 
     */
    const ENCRYPTION_METHOD = 'AES-256-CBC';

    /**
     * @param string $key The encryption key
     * @throws \InvalidArgumentException if $key is not given or is invalid
     * @throws \RuntimeException if openssl extension is not installed
     */
    public function __construct($key = null)
    {
        if (empty($key) || !is_string($key)) {
            throw new \InvalidArgumentException(sprintf(
                "You need to specify a valid string as key in order to use %s",
                __CLASS__
            ));
        }

        if (!extension_loaded('openssl')) {
            throw new \RuntimeException(sprintf(
                "You need OpenSSL extension installed in order to use %s",
                __CLASS__
            ));
        }

        $this->key = $key;
        $this->iv_size = openssl_cipher_iv_length(self::ENCRYPTION_METHOD);
    }

    /**
     * Simple data Encryption using OpenSSL
     *
     * @param string $data The data to encrypt
     * @return string|false
     */
    public function encrypt($data)
    {
        $iv = openssl_random_pseudo_bytes($this->iv_size);
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
    public function decrypt($data)
    {
        $decoded = base64_decode($data);
        $iv = substr($decoded, 0, $this->iv_size);
        $encrypted = substr($decoded, $this->iv_size);

        return openssl_decrypt(
            $encrypted,
            self::ENCRYPTION_METHOD, 
            $this->key, 
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}
