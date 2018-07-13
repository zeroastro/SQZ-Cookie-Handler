<?php namespace Sqz\CookieHandler\Tests\CookieHandler;

use Sqz\CookieHandler\Cookie;
use PHPUnit\Framework\TestCase;

/**
 * Cookie Test Suite - This is the TestCase class for Cookie
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 *
 * @group sqz-cookie-handler-test
 */
class CookieTest extends TestCase
{
    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * @var array
     */
    protected $cookieArray;

    /**
     * Setup the Cookie Variable for Testing
     */
    public function setUp()
    {
        $this->cookieArray = [
            'name' => 'testName',
            'value' => 'testValue',
            'expiration' => 65535,
            'path' => 'testPath',
            'domain' => 'testDomain',
            'secure' => true,
            'httpOnly' => true
        ];

        $this->cookie = Cookie::createFromJSON(json_encode($this->cookieArray));
    }

    /**
     * Test constructor with empty name
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithEmptyNameThrowsException()
    {
        $cookie = new Cookie('', 'value');
    }

    /**
     * Test the constructor
     */
    public function testConstructorDefaultValues()
    {
        $cookie = new Cookie('name', 'value');

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertEquals('name', $cookie->getName());
        $this->assertEquals('value', $cookie->getValue());
        $this->assertEquals(0, $cookie->getExpiration());
        $this->assertEquals('/', $cookie->getPath());
        $this->assertEquals('', $cookie->getDomain());
        $this->assertFalse($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
    }

    /**
     * Test getName()
     */
    public function testGetName()
    {
        $this->assertEquals('testName', $this->cookie->getName());
    }

    /**
     * Test getValue()
     */
    public function testGetValue()
    {
        $this->assertEquals('testValue', $this->cookie->getValue());
    }

    /**
     * Test getExpiration()
     */
    public function testGetExpiration()
    {
        $this->assertEquals(65535, $this->cookie->getexpiration());
    }

    /**
     * Test isExpired()
     */
    public function testIsExpired()
    {
        $this->assertTrue($this->cookie->isExpired());
    }

    /**
     * Test getPath()
     */
    public function testGetPath()
    {
        $this->assertEquals('testPath', $this->cookie->getPath());
    }

    /**
     * Test getDomain()
     */
    public function testGetDomain()
    {
        $this->assertEquals('testDomain', $this->cookie->getDomain());
    }

    /**
     * Test isSecure()
     */
    public function testIsSecure()
    {
        $this->assertTrue($this->cookie->isSecure());
    }

    /**
     * Test getIsHttpOnly()
     */
    public function testIsHttpOnly()
    {
        $this->assertTrue($this->cookie->isHttpOnly());
    }

    /**
     * Test getJSON()
     */
    public function testGetJSON()
    {
        $this->assertSame(json_encode($this->cookieArray), $this->cookie->getJSON());
    }
}
