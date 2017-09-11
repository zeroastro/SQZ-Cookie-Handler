<?php
/**
 * Cookie Test Suite - This is the TestCase class for Cookie
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 *
 * @group sqz-cookie-handler-test
 */

namespace SqzCookieHandlerTests\CookieHandler;

use SqzCookieHandler\Cookie;

class CookieTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the Cookie Variable for Testing
     */
    public function setUp()
    {
        $cookieArray = [
            'name' => 'testName',
            'value' => 'testValue',
            'expire' => 65535,
            'path' => 'testPath',
            'domain' => 'testDomain',
            'secure' => true,
            'httpOnly' => true
        ];

        $this->cookie = Cookie::createFromJSON(json_encode($cookieArray));
    }

    /**
     * Test constructor with empty name
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithEmptyName()
    {
        $cookie = new Cookie('');
    }

    /**
     * Test constructor without value
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithoutValue()
    {
        $cookie = new Cookie('name');
    }

    /**
     * Test constructor with invalid expires
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithInvalidExpires()
    {
        $cookie = new Cookie('name', 'value', false);
    }

    /**
     * Test the constructor
     */
    public function testConstructor()
    {
        $cookie = new Cookie('name', 'value');
        $this->assertInstanceOf(Cookie::class, $cookie);
    }

    /**
     * Test the costruction with a DateTime Object as Expires Time
     */
    public function testCookieWithDateTimeExpires()
    {
        $expireDT = new \DateTime();
        $cookie = new Cookie('name', 'value', $expireDT);

        $this->assertEquals($expireDT->format('U'), $cookie->getExpires());
    }

    /**
     * Test the costruction with a string as Expires Time
     */
    public function testCookieWithStringExpires()
    {
        $expireString = '+1 day';
        $expire = strtotime($expireString);
        $cookie = new Cookie('name', 'value', $expireString);

        $this->assertEquals($expire, $cookie->getExpires());
    }

    /**
     * Test getName()
     */
    public function testGetName()
    {
        $this->assertEquals($this->cookie->getName(), 'testName');
    }

    /**
     * Test getValue()
     */
    public function testGetValue()
    {
        $this->assertEquals($this->cookie->getValue(), 'testValue');
    }

    /**
     * Test getExpires()
     */
    public function testGetExpires()
    {
        $this->assertEquals($this->cookie->getExpires(), 65535);
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
        $this->assertEquals($this->cookie->getPath(), 'testPath');
    }

    /**
     * Test getDomain()
     */
    public function testGetDomain()
    {
        $this->assertEquals($this->cookie->getDomain(), 'testDomain');
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
        $this->assertTrue($this->cookie->isSecure());
    }
}
