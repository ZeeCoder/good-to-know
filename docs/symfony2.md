## Symfony2 Integration

#### services.yml
```yml
parameters:
    # Adding the configuration as a service parameter.
    good_to_know:
        - {text: translation_key, group: [A]}

services:
    service.good_to_know:
        class: ZeeCoder\GoodToKnow\GoodToKnow
        arguments: ["%good_to_know%", [@translator]]
        configurator: [@service.good_to_know_configurator, configure]

    service.good_to_know_configurator:
        class: AppBundle\Configurator\GoodToKnowConfigurator
        arguments: [@service.good_to_know]
```

#### GoodToKnowConfigurator.php
[More on service configurators](http://symfony.com/doc/current/components/dependency_injection/configurators.html)
```php
<?php

namespace AppBundle\Configurator;

class GoodToKnowConfigurator
{
    private $goodToKnow;

    public function __construct($goodToKnow)
    {
        $this->goodToKnow = $goodToKnow;
    }

    public function configure()
    {
        // You can do anything here with the package
        $this->goodToKnow->addParameter('%upload_max_filesize%', function() {
            return ini_get('upload_max_filesize');
        });
    }
}
```
