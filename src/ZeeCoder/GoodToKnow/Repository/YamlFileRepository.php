<?php

namespace ZeeCoder\GoodToKnow\Repository;

use Symfony\Component\Yaml\Yaml;

class YamlFileRepository extends AbstractFileRepository
{
    protected function loadData($path)
    {
        return Yaml::parse(file_get_contents($path));
    }
}
