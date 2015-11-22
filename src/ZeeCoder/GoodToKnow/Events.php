<?php

namespace ZeeCoder\GoodToKnow;

/**
 * Events reside under the ZeeCoder\GoodToKnow\Event namespace.
 */
final class Events
{
    /**
     * Gets fired for every Fact a repository returned.
     * Passes a TransformEvent event.
     */
    const TRANSFORM = 'good_to_know.transform';

    /**
     * Passes the CollectionEvent event before the transform events occur.
     */
    const PRE_TRANSFORM = 'good_to_know.pre_transform';

    /**
     * Passes the CollectionEvent event after the transform events occur.
     */
    const POST_TRANSFORM = 'good_to_know.post_transform';
}
