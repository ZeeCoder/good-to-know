# Silex2 Integration

```php
// Registering the service
$app['service.good_to_know'] = function() use ($app) {
    $goodToKnow = new GoodToKnow(
        // Suppose we have a yaml file containing the configuration
        Yaml::parse(file_get_contents(__DIR__ . '/config/good_to_know.yml'))
    );

    // Sf translator
    $goodToKnow->addTranslator($app['translator']);

    // A closure parameter
    $goodToKnow->addParameter('%upload_max_filesize%', function() {
        return ini_get('upload_max_filesize');
    });
    // A dummy parameter
    $goodToKnow->addParameter('%paramname%', function() {
        return 'paramvalue';
    });

    return $goodToKnow;
};
```
