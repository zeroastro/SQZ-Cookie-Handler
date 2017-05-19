<?php
/**
 * Cookie Test Suite - This is the TestCase class for CookieHandler
 *
 * @author Salvatore Q Zeroastro <zeroastro@gmail.com>
 *
 * @group sqz-cookie-handler-test
 */

namespace SQZ_CookieHandler_Test;

use \SQZ_CookieHandler\Cookie;
use \SQZ_CookieHandler\CookieHandler;

class CookieHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup the Cookie Handler Variable for Testing
     */
    public function setUp()
    {
        $this->cookieHandler = new CookieHandler();
        $this->cookie = new Cookie('testName', 'testValue');
    }

    /**
     * Test the Constructor
     */
     public function testConstructor()
     {
         $this->assertInstanceOf(CookieHandler::class, $this->cookieHandler);
     }

    /**
     * Test the saveCookie() function
     *
     * @runInSeparateProcess
     */
    public function testSaveCookie()
    {
        $this->assertTrue($this->cookieHandler->saveCookie($this->cookie));
    }

    /**
     * Test the getCookie() function
     */
    public function testGetCookie()
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
     * Test the removeCookie() function
     *
     * @runInSeparateProcess
     */
    public function testRemoveCookie()
    {
        $this->assertTrue($this->cookieHandler->removeCookie($this->cookie->getName()));
    }       

    /**
     * Test the Constructor using encryption key
     *
     * @requires extension openssl
     */
    public function testConstructorSecure()
    {
        $cookieHandlerSecure = new CookieHandler('testKeyForEncryption');

        $this->assertInstanceOf(CookieHandler::class, $cookieHandlerSecure);
    }

     /**
      * Test the saveCookie() function with encryption
      *
      * @requires extension openssl
      * @runInSeparateProcess
      */
    public function testSaveCookieSecure()
    {
        $cookieHandlerSecure = new CookieHandler('testKeyForEncryption');

        $this->assertTrue($cookieHandlerSecure->saveCookie($this->cookie, true));
    }

    /**
     * Test the getCookie() function with an empty value
     */
    public function testGetCookieEmpty()
    {
        $cookie = $this->cookieHandler->getCookie('IDontExist');

        $this->assertNull($cookie);
    } 

    /**
     * Test the getCookie() function with decryption
     *
     * @requires extension openssl
     */
    public function testGetCookieSecure()
    {
        /* original value is 'testValue' */
        $_COOKIE = [
            'testName' => 'vBHcIedwCAgamuWDIMnVPBmAWoHuuN0mdD/apccBzPsOAkhZuYM5UQ/QJkplCKdIRa6aWsNsPjQpumTb41zaPkOd2usbGgyWKFCe93Mm2v2C03JXnSNTRv+WfuUpnAnsS+zbAnBdUbbQDk8gFs0oxgqXZhP9rc5nNzggYrXqthg='
        ];

        $cookieHandlerSecure = new CookieHandler('testKeyForEncryption');

        $cookie = $cookieHandlerSecure->getCookie('testName', true);

        $this->assertInstanceOf(Cookie::class, $cookie);
        $this->assertEquals('testValue', $cookie->getValue());
    } 

    /**
     * Test Encryption/decryption
     *
     * @requires extension openssl
     */
    public function testCryptDecrypt()
    {
        $reflection = new \ReflectionClass(CookieHandler::class);

        $encrypt = $reflection->getMethod('encrypt');
        $encrypt->setAccessible(true);

        $decrypt = $reflection->getMethod('decrypt');
        $decrypt->setAccessible(true);

        $cleanValue = 'testValue';
        $cookieHandlerSecure = new CookieHandler('testKeyForEncryption');

        $encValue = $encrypt->invoke($cookieHandlerSecure, $cleanValue);
        $decValue = $decrypt->invoke($cookieHandlerSecure, $encValue);

        $this->assertNotEquals($cleanValue, $encValue);
        $this->assertEquals($cleanValue, $decValue);    
    }

}