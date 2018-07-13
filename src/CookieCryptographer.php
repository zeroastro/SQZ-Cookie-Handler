<?php

namespace Sqz\CookieHandler;

use InvalidArgumentException;

/**
 * Utility class to perform simple data encryption/decryption
 *
 * This class has been modified for the current project
 * Original Class Gist: https://gist.github.com/zeroastro/6d6d22d30816638b16ba835f909a5135
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 *
 * @copyright MIT License
 */
class CookieCryptographer implements CryptographerInterface
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
    protected $ivSize;

    /**
     * Encryption Method
     *
     * @var string
     */
    const ENCRYPTION_METHOD = 'AES-256-CBC';

    /**
     * @param string $key The encryption key
     *
     * @throws \InvalidArgumentException if $key is not given or invalid
     */
    public function __construct(string $key = '')
    {
        if (empty($key)) {
            throw new InvalidArgumentException(sprintf(
                "You need to specify a valid string as key in order to use %s",
                __CLASS__
            ));
        }

        $this->key = $key;
        $this->ivSize = openssl_cipher_iv_length(self::ENCRYPTION_METHOD);
    }

    /**
     * Simple data Encryption using OpenSSL
     *
     * @param string $data The data to encrypt
     *
     * @return string|false
     */
    public function encrypt(string $data)
    {
        $iv = openssl_random_pseudo_bytes($this->ivSize);
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
     *
     * @return string|false
     */
    public function decrypt(string $data)
    {
        $decoded = base64_decode($data);
        $iv = substr($decoded, 0, $this->ivSize);
        $encrypted = substr($decoded, $this->ivSize);

        return openssl_decrypt(
            $encrypted,
            self::ENCRYPTION_METHOD,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );
    }
}
