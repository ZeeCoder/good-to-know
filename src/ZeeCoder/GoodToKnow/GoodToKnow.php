<?php

namespace ZeeCoder\GoodToKnow;

class GoodToKnow
{
    private $config;
    private $translator;
    private $translationDomain = 'good_to_know';
    private $parameters = [];

    /**
     * @param array $config
     * @param array $translatorConfig If given, then it is passed to the
     * `addTranslator` method.
     */
    public function __construct(array $config, array $translatorConfig = null)
    {
        $this->config = $config;

        if ($translatorConfig !== null) {
            call_user_func_array([$this, 'addTranslator'], $translatorConfig);
        }
    }

    /**
     * Gets the good to know strings for a given group.
     * @param string $group
     * @return array An array of good to know strings
     */
    public function getAllByGroup($group = null)
    {
        if ($group === null) {
            throw new \RuntimeException('Missing "group" parameter.');
        }

        $groupStrings = [];
        foreach ($this->config as $stringData) {
            // Converting the group parameter to array
            if (!is_array($stringData['group'])) {
                $stringData['group'] = [$stringData['group']];
            }

            // Checking the current string if it's in the requested group
            if (!in_array($group, $stringData['group'])) {
                continue;
            }

            // Getting the string without parameters
            $rawString = $this->translator === null
                ? $stringData['text']
                : $this->translator->trans($stringData['text'], [], $this->translationDomain);

            // Adding the parameters to the string
            $groupStrings[] = $this->addParametersToString($rawString);
        }

        return $groupStrings;
    }

    /**
     * Returns a string which has the injected parameters.
     * @param string $string The string the parameters need to be injected into.
     */
    private function addParametersToString($string)
    {
        $params = $this->getParametersForString($string);
        return str_replace($params['names'], $params['values'], $string);
    }

    /**
     * Inspects the given string, and returns an array of parameters it needs.
     * @param string $string
     * @return An array of parameters that can be used for a str_replace call
     */
    private function getParametersForString($string)
    {
        $params = [
            'names' => [],
            'values' => [],
        ];

        foreach ($this->parameters as $parameterData) {
            if (strpos($string, $parameterData['name']) === false) {
                continue;
            }

            $params['names'][] = $parameterData['name'];
            $params['values'][] =
                is_callable($parameterData['value'])
                    ? call_user_func($parameterData['value'])
                    : $parameterData['value'];
        }

        return $params;
    }

    /**
     * Adds a translator, which is used to translate the texts.
     * @param Object $translator The translator service
     * @param string $translationDomain The translation domain used by the translator.
     */
    public function addTranslator($translator, $translationDomain = null)
    {
        $this->translator = $translator;
        if ($translationDomain !== null) {
            $this->translationDomain = $translationDomain;
        }
    }

    /**
     * Adds a parameter, which can be used in the texts.
     * @param string $name The name which can appear in the texts.
     * @param mixed $value Can be a value, which can be inserted into the text,
     * or a callable.
     */
    public function addParameter($name, $value)
    {
        $this->parameters[] = [
            'name' => $name,
            'value' => $value,
        ];
    }
}
