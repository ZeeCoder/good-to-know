# Good To Know
[![Project Status](http://stillmaintained.com/ZeeCoder/good-to-know.png)](http://stillmaintained.com/ZeeCoder/good-to-know)
[![Build Status](https://travis-ci.org/ZeeCoder/good-to-know.svg?branch=master)](https://travis-ci.org/ZeeCoder/good-to-know)
[![Version](http://img.shields.io/packagist/v/zeecoder/good-to-know.svg?style=flat)](https://packagist.org/packages/zeecoder/good-to-know)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d35a0363-0bd0-4dc4-97f4-e5cc5e8cc90e/mini.png)](https://insight.sensiolabs.com/projects/d35a0363-0bd0-4dc4-97f4-e5cc5e8cc90e)

## What is this?

This package helps you collect "good-to-know" facts for different features in
your application.
The facts can contain "parameters", and they can be written in multiple
languages.

An example would be: "The maximum size of an uploaded file is %max_size%.".

## Installation

run `composer require zeecoder/good-to-know`, or add it to your app's
composer.json manually.

## How to

I'll show a very simple example in plain PHP, but the package is really easy to
integrate into any framework.

Here are some examples:

 - [Silex2](docs/silex2.md)
 - [Symfony2](docs/symfony2.md)

## Plain PHP

```php
<?php

use ZeeCoder\GoodToKnow\GoodToKnow;

require 'vendor/autoload.php';

// Adding some collectable facts
$GoodToKnow = new GoodToKnow([
    ['text' => 'One fact.', 'group' => 'A'],
    ['text' => 'Another fact.', 'group' => ['A', 'B']],
    ['text' => 'Even more fact!', 'group' => 'B'],
]);

// An array of texts -> profit
$factsForGroupB = $GoodToKnow->getAllByGroup('B');
```

## Adding parameters

Parameters are automatically injected into the texts.

```php
<?php

use ZeeCoder\GoodToKnow\GoodToKnow;

require 'vendor/autoload.php';

// Data
$GoodToKnow = new GoodToKnow([
    [
        'text' => 'This app is using the "%pkg_name%" Composer package written by %author%.',
        'group' => 'A'
    ],
    [
        'text' => 'The "upload_max_filesize" is "%upload_max_filesize%".',
        'group' => 'A'
    ],
]);

// Adding some parameters
$GoodToKnow->addParameter('%author%', 'ZeeCoder');
$GoodToKnow->addParameter('%pkg_name%', 'GoodToKnow');
$goodToKnow->addParameter('%upload_max_filesize%', function() {
    return ini_get('upload_max_filesize');
});

// Getting texts from group 'A'
$factsForGroupA = $GoodToKnow->getAllByGroup('A');
```

## Adding translations

For translations, a translator with the api of the [Symfony Translation Component](http://symfony.com/doc/current/components/translation/introduction.html#using-message-domains)
is assumed.

This means, that internally the `$translator->trans($key, $params, $domain)`
method will be called, with 'good_to_know' as the default domain.

```php
<?php

use ZeeCoder\GoodToKnow\GoodToKnow;

require 'vendor/autoload.php';

// Suppose we have a $translator

// Data
$GoodToKnow = new GoodToKnow(
    [
        ['text' => 'translation_key', 'group' => 'A'],
    ]
    // The translator could alsobe added here
    // ,[$translator, 'good_to_know']
);

// Adding the translator
// (Specifying the domain is optional.)
$GoodToKnow->addTranslator($translator, 'good_to_know');

// Getting texts from group 'A'
$factsForGroupA = $GoodToKnow->getAllByGroup('A');
```

Parameters are working just the same with translations.

## License
[MIT](LICENSE)

## Credits
I'd like to give special thanks to [Leviatus21](https://github.com/Leviatus21), who helped with the initial
planning, and wrote the first implementations.
