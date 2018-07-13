<?php

namespace Sqz\CookieHandler\Tests\CookieHandler;

use PHPUnit\Framework\TestCase;
use Sqz\CookieHandler\{Cookie, CookieHandler, CryptographerInterface};

/**
 * Cookie Test Suite - This is the TestCase class for CookieHandler
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 *
 * @group sqz-cookie-handler-test
 */
class CookieHandlerTest extends TestCase
{
    /**
     * @var CookieHandler
     */
    protected $cookieHandler;

    /**
     * @var CookieHandler
     */
    protected $cookieHandlerSecure;

    /**
     * @var CryptographerInterface
     */
    protected $cryptographer;

    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * Setup the Cookie Handler Variable for Testing
     */
    public function setUp()
    {
        $this->cookie = new Cookie('testName', 'testValue');

        $this->cryptographer = $this->createMock(CryptographerInterface::class);

        $this->cookieHandler = new CookieHandler();
        $this->cookieHandlerSecure = new CookieHandler($this->cryptographer);
    }

    /**
     * Test the Constructor
     */
    public function testConstructorWithoutCryptographer()
    {
        $this->assertInstanceOf(CookieHandler::class, $this->cookieHandler);
    }

    /**
     * Test the Constructor
     */
    public function testConstructorWithCryptographer()
    {
        $this->assertInstanceOf(CookieHandler::class, $this->cookieHandlerSecure);
    }

    /**
     * Test the saveCookie() function
     *
     * @runInSeparateProcess
     */
    public function testSaveCookieWithoutCryptographerReturnsTrue()
    {
        $this->assertTrue($this->cookieHandler->saveCookie($this->cookie));
    }

    /**
     * Test the getCookie() function
     */
    public function testGetCookieWithoutCryptographerReturnsTheCookie()
    {
        $_COOKIE = [
            'testName' => json_encode([
                'name' => 'testName',
                'value' => 'testValue'
            ])
        ];

        $cookie = $this->cookieHandler->getCookie('testName');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertEquals('testValue', $cookie->getValue());
    }

    /**
     * Test the getCookie() function with an empty value
     */
    public function testGetCookieReturnsNullIfCookieDoesNotExist()
    {
        $cookie = $this->cookieHandler->getCookie('IDontExist');

        $this->assertNull($cookie);
    }

    /**
     * Test the removeCookie() function
     *
     * @runInSeparateProcess
     */
    public function testRemoveCookieReturnsTrueWhenCookieExists()
    {
        $this->assertTrue($this->cookieHandler->removeCookie($this->cookie->getName()));
    }

    /**
     * Test the removeCookie() function
     *
     * @runInSeparateProcess
     */
    public function testRemoveCookieReturnsFalseWhenCookieDoesNotExist()
    {
        $this->assertFalse($this->cookieHandler->removeCookie('i-dont-exist'));
    }

    /**
     * Test the saveCookie() function with encryption
     *
     * @requires extension openssl
     * @runInSeparateProcess
     */
    public function testSaveCookieWithCryptographerReturnsTrue()
    {
        $this->cryptographer->method('encrypt')->willReturn('test-cookie-value-encrypted');

        $this->assertTrue($this->cookieHandlerSecure->saveCookie($this->cookie));
    }

    /**
     * Test the getCookie() function with decryption
     *
     * @requires extension openssl
     */
    public function testGetCookieWithCryptographerReturnsTheCookie()
    {
        $_COOKIE = [
            'testName' => 'test-cookie-value-encrypted'
        ];

        $expected = 'test-cookie-value-decrypted';

        $returned = json_encode(
            [
                'name' => 'testName',
                'value' => $expected
            ]
        );

        $this->cryptographer->method('decrypt')->willReturn($returned);

        $cookie = $this->cookieHandlerSecure->getCookie('testName');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertEquals($expected, $cookie->getValue());
    }
}
