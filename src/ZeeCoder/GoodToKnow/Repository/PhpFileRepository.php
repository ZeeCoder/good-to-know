<?php

namespace ZeeCoder\GoodToKnow\Repository;

class PhpFileRepository extends AbstractFileRepository
{
    protected function loadData($path)
    {
        return require $path;
    }
}
