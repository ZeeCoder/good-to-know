<?php

namespace ZeeCoder\GoodToKnow;

/**
 * A simple parameter injector which injects parameters to a given string, where
 * the parameters are stored in an assoc. array.
 */
class ParameterInjector
{
    /**
     * An assoc. array where the parameters are stored.
     * The values could be callables too, which are executed upon injection.
     * @var array
     */
    private $parameters = [];

    /**
     * Injects all the parameters found in the given $text string, then returns
     * the result.
     * @param string $text if a non-string variable is given, then it gets
     * returned without injection.
     * @return string
     */
    public function inject($text)
    {
        if (!is_string($text)) {
            return $text; // nothing to do here
        }

        foreach ($this->parameters as $key => $parameter) {
            if (strpos($text, $key) === false) {
                continue;
            }

            if (is_callable($parameter)) {
                $parameter = call_user_func($parameter);
            }

            $text = str_replace($key, $parameter, $text);
        }

        return $text;
    }

    /**
     * Adds a parameter that can occur in Fact texts.
     * @param string $key This is the key which will be searched for in the text.
     * @param string|Callable $data
     * @throws \RuntimeException if a parameter with the given $key was already
     * added.
     * @return $this
     */
    public function addParameter($key, $data)
    {
        if (!is_string($key)) {
            throw new \RuntimeException('Only strings can be given as parameter keys. Got "' . gettype($key) . '".');
        }

        if (!is_string($data) && !is_callable($data)) {
            throw new \RuntimeException('The "data" parameter must either be a string or a callable. . Got "' . gettype($key) . '".');
        }

        if (isset($this->parameters[$key])) {
            throw new \RuntimeException('The parameter key "' . $key . '" is already set.');
        }

        $this->parameters[$key] = $data;

        return $this;
    }

    /**
     * Overwrites the whole parameters array with the given one.
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
