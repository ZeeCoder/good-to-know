<?php

namespace ZeeCoder\GoodToKnow\Repository;

use ZeeCoder\GoodToKnow\Fact;

/**
 * A generic File Repository.
 */
abstract class AbstractFileRepository extends FactObjectRepository
{
    /**
     * The way the data is loaded must be implemented by the child class.
     * @param $path
     * @return array An array of data with two keys: "text" and "groups" where
     * text must be a string and groups must be an array of strings.
     */
    abstract protected function loadData($path);

    public function __construct($path)
    {
        $this->checkFileExistence($path);

        foreach ($this->mapDataToFactObjects($this->loadData($path)) as $fact) {
            $this->attach($fact);
        }
    }

    /**
     * Checks whether the file given by $path exists or not.
     * @param $path
     * @throws \RuntimeException if the file does not exists.
     */
    protected function checkFileExistence($path)
    {
        if (!is_file($path)) {
            throw new \RuntimeException('The required file "' . $path . '" does not exist.');
        }
    }

    /**
     * Converts an array of data to an array of Fact objects.
     * @param array $data An array of data with two keys: "text" and "groups"
     * where text must be a string and groups must be an array of strings.
     * @return array
     */
    private function mapDataToFactObjects(array $data)
    {
        return array_map(function($factData) {
            return (new Fact)
                ->setText($factData['text'])
                ->setGroups($factData['groups'])
            ;
        }, $data);
    }
}
