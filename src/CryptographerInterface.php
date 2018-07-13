<?php

namespace Sqz\CookieHandler;

/**
 * Interface CryptographerInterface
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 */
interface CryptographerInterface
{
    /**
     * @param string $data
     *
     * @return string|false
     */
    public function encrypt(string $data);

    /**
     * @param string $data
     *
     * @return string|false
     */
    public function decrypt(string $data);
}
