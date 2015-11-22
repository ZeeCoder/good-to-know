<?php

namespace ZeeCoder\GoodToKnow\Repository;

use ZeeCoder\GoodToKnow\Fact;

/**
 * A simple implementation of the RepositoryInterface to store
 * ZeeCoder\GoodToKnow\Fact objects.
 */
class FactObjectRepository extends \SplObjectStorage implements RepositoryInterface
{
    /**
     * @param object $fact
     * @param boolean $inf
     * @return $this
     */
    public function attach($fact, $inf = null)
    {
        if ($fact instanceof Fact === false) {
            throw new \RuntimeException('Only Fact objects can be attached to this repository.');
        }

        parent::attach($fact, $inf);

        return $this;
    }

    /**
     * @param array|null $groups An array of group strings.
     * @return \SplObjectStorage A collection of Fact objects.
     */
    public function findAllByGroups(array $groups = null)
    {
        if (null === $groups || count($groups) === 0) {
            return $this;
        }

        $filtered = new \SplObjectStorage;
        foreach ($this as $fact) {
            $intersect = array_intersect($fact->getGroups(), $groups);
            if (count($intersect) !== 0) {
                $filtered->attach($fact);
            }
        }

        return $filtered;
    }
}
