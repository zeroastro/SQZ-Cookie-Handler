# SQZ Cookie Handler
[![Build Status](https://travis-ci.org/zeroastro/SQZ-Cookie-Handler.svg?branch=master)](https://travis-ci.org/zeroastro/SQZ-Cookie-Handler)

## About
This is a Simple PHP Cookie Handler which uses the OpenSSL extension to perform data encryption/decryption.
SQZ Cookie Handler is composed by two components:

- Cookie: This class represents a single Cookie
- CookieHandler: This class is the Cookie Manager

Creating a single Cookie won't save the Cookie into the Storage. To perform this operation, we need the Cookie Handler. Each Cookie will be saved as a JSON string containing all the fields of the cookie. Thanks to this, further we will be able to know all the informations about the Cookie, and not only its value. It is highly recommended to use the encryption.

## License
SQZ Cookie Handler is released under MIT License 
https://github.com/zeroastro/SQZ-Cookie-Handler/blob/master/LICENSE

## Installation
You can install SQZ Cookie handler via Composer
```sh
$ composer require zeroastro/sqz-cookie-handler:dev-master
```

## Usage

### Cookie
To init a Cookie object we can use the `new` statement or create with a JSON using the `createFromJSON` static function 
```
$cookieWithNew = new Cookie( 
    string NAME , 
    string VALUE [, 
    int|DateTime EXPIRATION, 
    string PATH, 
    string DOMAIN, 
    bool HTTPS_ONLY, 
    bool HTTP_ONLY ] 
);
$cookieWithJson = Cookie::createFromJSON({ 
    "name":"NAME", 
    "value":"VALUE", 
    "expire":"EXPIRATION", 
    "path":"PATH", 
    "domain":"DOMAIN", 
    "secure":"HTTPS_ONLY",
    "httpOnly":"HTTP_ONLY"
});
```
Only Name and Value are Mandatory. Default values for the other fields are:
- Expiration: 0 (session)
- Path: /
- Domain: NULL
- Secure: false
- HttpOnly: true

The Cookie class provides getters and setters for each field.

### CookieHandler
You can init the Cookie Handler with or without an encryption key as parameter. 
To perform a data encryption it's mandatory to set up a key.

```
$cookieHandler = new CookieHandler(); // Encryption disabled
$cookieHandlerSecure = new CookieHandler(string ENCRYPTION_KEY); // Encryption enabled
```

Once the class has ben initialised, you can Save, Retrieve and Remove cookie. 
To update a cookie just save a new cookie with the same name.

```
$cookieHandler->saveCookie(Cookie COOKIE [, bool ENCRYPT]);
$cookie = $cookieHandler->getCookie(string COOKIE_NAME [, bool DECRYPT]);
$cookieHandler->removeCookie(string COOKIE_NAME);
```
Encryption/Decryption are `false` by default.