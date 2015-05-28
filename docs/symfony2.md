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

---

If you want to include the configuration from a separate file, you can do the
following:

#### app/config/good_to_know.yml
```yml
- {text: translation_key, group: A}
- {text: translation_key2, group: A}
```

#### services.yml
```yml
# No need for service parameters now
services:
    service.good_to_know:
        class: ZeeCoder\GoodToKnow\GoodToKnow
        # no need for arguments
        configurator: [@service.good_to_know_configurator, configure]

    service.good_to_know_configurator:
        class: AppBundle\Configurator\GoodToKnowConfigurator
        arguments: [@service.good_to_know, @translator, "%kernel.root_dir%"]
```

#### GoodToKnowConfigurator.php
```php
<?php

namespace AppBundle\Configurator;

use Symfony\Component\Yaml\Yaml;

class GoodToKnowConfigurator
{
    private $goodToKnow;
    private $translator;
    private $kernelRootDir;

    public function __construct($goodToKnow, $translator, $kernelRootDir)
    {
        $this->goodToKnow = $goodToKnow;
        $this->translator = $translator;
        $this->kernelRootDir = $kernelRootDir;
    }

    public function configure()
    {
        $this->goodToKnow->addConfiguration(
            Yaml::parse(file_get_contents($this->kernelRootDir . '/config/good_to_know.yml'))
        );
        $this->goodToKnow->addTranslator($this->translator);

        $this->goodToKnow->addParameter('%upload_max_filesize%', function() {
            return ini_get('upload_max_filesize');
        });
    }
}
```
