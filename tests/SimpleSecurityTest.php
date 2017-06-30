<?php
/**
 * Cookie Test Suite - This is the TestCase class for the SimpleSecurity Class
 *
 * @author Salvo Quaranta (Zeroastro) <salvoquaranta@gmail.com>
 *
 * @group sqz-cookie-handler-test
 */

namespace SQZ_CookieHandler_Test;

use \SQZ_CookieHandler\SimpleSecurity;

class SimpleSecurityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Set up data for the tests
     */ 
    public function setUp()
    {
        $this->cleanValue = 'sqz-test-string';
        $this->security = new SimpleSecurity('sqz-test-key');
    }

    /**
     * Tests constructor without passing a key
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithoutKey()
    {
        $security = new SimpleSecurity();
    }

    /**
     * Tests constructor passing an empty key
     *
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorWithEmptyKey()
    {
        $security = new SimpleSecurity('');
    }

    /**
     * Tests the Constructor
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(SimpleSecurity::class, $this->security);
    }

    /**
     * Tests the Encryption/Decryption
     */
    public function testEncryptDecrypt()
    {
        $encValue = $this->security->encrypt($this->cleanValue);
        $decValue = $this->security->decrypt($encValue);

        $this->assertSame($this->cleanValue, $decValue);
    } 
}