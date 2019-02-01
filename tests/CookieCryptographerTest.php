<?php
namespace Sqz\CookieHandler\Tests\CookieHandler;

use PHPUnit\Framework\TestCase;
use Sqz\CookieHandler\CookieCryptographer;
use Sqz\CookieHandler\Contracts\CryptographerInterface;

/**
 * Cookie Test Suite - This is the TestCase class for CookieCryptohrapher
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 *
 * @group sqz-cookie-handler-test
 */
class CookieCryptographerTest extends TestCase
{
    /**
     * @var CookieCryptographer
     */
    protected $cookieCryptographer;

    public function setUp()
    {
        $this->cookieCryptographer = new CookieCryptographer('testKey');
    }

    public function testCookieCryptographerConstructor()
    {
        $this->assertInstanceOf(CookieCryptographer::class, $this->cookieCryptographer);
    }

    public function testIsACryptographerInterfaceInstance()
    {
        $this->assertInstanceOf(CryptographerInterface::class, $this->cookieCryptographer);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCookieCryptographerConstructorThrowsExceptionForEmptyKey()
    {
        (new CookieCryptographer(''));
    }

    public function testDataEncryptedThenDecryptedMatch()
    {
        $input = 'testString';

        $encrypted = $this->cookieCryptographer->encrypt($input);

        $decrypted = $this->cookieCryptographer->decrypt($encrypted);

        $this->assertSame($input, $decrypted);
    }
}
