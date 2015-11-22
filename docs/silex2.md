# Silex2 Integration

```php
use ZeeCoder\GoodToKnow\GoodToKnow;
use ZeeCoder\GoodToKnow\Repository\YamlFileRepository;
use ZeeCoder\GoodToKnow\ParameterInjector;
use ZeeCoder\GoodToKnow\Events;
use ZeeCoder\GoodToKnow\Listener\ParameterListener;
use ZeeCoder\GoodToKnow\Listener\TranslationListener;

// Registering the service
$app['service.good_to_know'] = function() use ($app) {
    $gtk = new GoodToKnow(
        new YamlFileRepository(__DIR__ . '/config/good-to-know.yml'),
        $app['dispatcher']
    );

    // TranslationListener
    $app['dispatcher']->addListener(
        Events::TRANSFORM,
        new TranslationListener($app['translator'])
    );

    // ParameterListener
    $parameterInjector = (new ParameterInjector())
        // Registering parameters
        ->addParameter('%lorem%', 'ipsum')
        ->addParameter('%upload_max_filesize%', function() {
            return ini_get('upload_max_filesize');
        })
    ;

    $app['dispatcher']->addListener(
        Events::TRANSFORM,
        new ParameterListener($parameterInjector)
    );

    return $gtk;
};
```
