# Good To Know v2.0

[![Build Status](https://travis-ci.org/ZeeCoder/good-to-know.svg?branch=master)](https://travis-ci.org/ZeeCoder/good-to-know)
[![Version](http://img.shields.io/packagist/v/zeecoder/good-to-know.svg?style=flat)](https://packagist.org/packages/zeecoder/good-to-know)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d35a0363-0bd0-4dc4-97f4-e5cc5e8cc90e/mini.png)](https://insight.sensiolabs.com/projects/d35a0363-0bd0-4dc4-97f4-e5cc5e8cc90e)

## Description

The original purpose of this project was to have an easy way of collecting
good-to-know facts for certain pages / features that need explanation.

To achieve that, the code was engineered in a very generic way:

- Fetch all the data with a repository call,
- Apply transformations on the returned collection and/or it's elements via
Listeners.

Since the code uses listeners, it's very easy to add / remove functionality.

### Built-in features

- Parameter injection
- Translation support

## Integrations

- [Silex2](docs/silex2.md)
- [Symfony2](docs/symfony2.md)

## A simple example

```yml
# good-to-know.yml
# When loaded and fetched by the `YamlFileRepository`, these will be converted
# into `Fact` objects.
- text: Something important.
  groups: [feature1]
- text: Something important about feature2.
  groups: [feature2]
- text: Something important about both features.
  groups: [feature1, feature2]
```

```php
use ZeeCoder\GoodToKnow\GoodToKnow;
use ZeeCoder\GoodToKnow\Repository\YamlFileRepository;

// The `GoodToKnow` object acts as a wrapper around the given repository.
// Every call made to this object is forwarded to the repository.
// The listeners are fired after getting the result collection from the
// repository.
$gtk = new GoodToKnow(
    new YamlFileRepository(__DIR__ . '/good-to-know.yml')
    // A dispatcher could be passed as the second argument.
    // If no dispatcher is given, one gets created
);

// `findAllByGroups` is a method in the `YamlFileRepository` repository.
// It returns a collection of `ZeeCoder\GoodToKnow\Fact` objects.
// (In this case, an `\SplObjectStorage`.)
$collection = $gtk->findAllByGroups([
    'feature1'
]);

// The collection would have the "Something important." and
// "Something important about both features." texts as Fact objects.
```

## Listeners

Without listeners, the main `GoodToKnow` object doesn't do anything apart from
forwarding calls to the repository.

It gets interesting, when you throw some listeners into the mix.

### The `ParameterListener`

This listener's job is to inject registered parameters into good-to-know
strings.

```yml
# good-to-know.yml
# When loaded and fetched by the `YamlFileRepository`, these will be converted
# into `Fact` objects.
- text: Something important. %lorem%!
  groups: [feature1]
- text: Something important about feature2.
  groups: [feature2]
- text: Something important about both features: The upload max filesize is: %upload_max_filesize%.
  groups: [feature1, feature2]
```

```php
// ...
// Assuming the example above

use ZeeCoder\GoodToKnow\ParameterInjector;
use ZeeCoder\GoodToKnow\Events;
use ZeeCoder\GoodToKnow\Listener\ParameterListener;

// Getting the dispatcher, since we didn't provide one explicitly.
$dispatcher = $gtk->getDispatcher();

// Creating the ParameterInjector
$parameterInjector = (new ParameterInjector())
    // Registering parameters
    ->addParameter('%lorem%', 'ipsum')
    ->addParameter('%upload_max_filesize%', function() {
        return ini_get('upload_max_filesize');
    })
;

// Adding the listener.
// The TRANSFORM event is fired for every result returned by the repository.
$dispatcher->addListener(
    Events::TRANSFORM,
    new ParameterListener($parameterInjector)
);

$collection = $gtk->findAllByGroups([
    'feature1'
]);

// Now the collection would have the "Something important. ipsum!" and
// "Something important about both features: The upload max filesize is: 2M."
// texts as Fact objects.
```

### The `TranslationListener`

This listener assumes a translator implementing Symfony's `TranslatorInterface`.

```php
use ZeeCoder\GoodToKnow\Events;
use ZeeCoder\GoodToKnow\Listener\TranslationListener;

// Getting the dispatcher, since we didn't provide one explicitly.
$dispatcher = $gtk->getDispatcher();

$dispatcher->addListener(
    Events::TRANSFORM,
    new TranslationListener(
        // Assuming we have a Symfony `$translator` object.
        $translator
        // The second parameter, is the translation domain. Default: "good-to-know"
    )
);

// Now all Fact texts will be translated.
```

**Important**

The `TranslationListener` must be registered first, or with a higher priority
than the `ParameterListener`.

That way parameters can be injected after translations occured.

## Source

Consider looking at the source files, in order to enhance / alter basic functionality.

(Creating a Database Repository for example.)

- [Events](src/ZeeCoder/GoodToKnow/Events.php)
- [Repositories](src/ZeeCoder/GoodToKnow/Repository)
- [The Fact Object](src/ZeeCoder/GoodToKnow/Fact.php)

## License
[MIT](LICENSE)
