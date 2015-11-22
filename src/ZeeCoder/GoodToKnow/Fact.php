<?php

namespace ZeeCoder\GoodToKnow;

/**
 * A simple "good-to-know" Fact.
 */
class Fact
{
    protected $text = '';

    protected $groups = [];

    public function __toString()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param array $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    public function addGroup($group)
    {
        if (!is_string($group)) {
            throw new \RuntimeException('Only strings can be added as groups.');
        }

        $this->groups[] = $group;

        return $this;
    }

    /**
     * @return array
     */
    public function getGroups()
    {
        return $this->groups;
    }
}
