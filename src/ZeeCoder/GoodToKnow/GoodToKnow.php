<?php

namespace ZeeCoder\GoodToKnow;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ZeeCoder\GoodToKnow\Event\CollectionEvent;
use ZeeCoder\GoodToKnow\Event\TransformEvent;
use ZeeCoder\GoodToKnow\Repository\RepositoryInterface;

class GoodToKnow
{
    private $repository;
    private $dispatcher;

    /**
     * @param RepositoryInterface $repository
     * @param EventDispatcherInterface|null $dispatcher If no dispatcher is
     * given, one gets created.
     */
    public function __construct(
        RepositoryInterface $repository,
        EventDispatcherInterface $dispatcher= null
    ) {
        $this->repository = $repository;

        if (null === $dispatcher) {
            $this->dispatcher = new EventDispatcher;
        } else {
            $this->dispatcher = $dispatcher;
        }
    }

    /**
     * Forwards the call to the repository, then dispatches events.
     * @param array|null $groups
     * @throws \RuntimeException if the called method does not exist in the
     * repository
     * @return mixed The repository's results with transformations possibly
     * applied by listeners.
     */
    public function __call($methodName, array $arguments)
    {
        if (!method_exists($this->repository, $methodName)) {
            throw new \RuntimeException('`' . $methodName . '` does not exists in the given repository.');
        }

        $factCollection = call_user_func_array([$this->repository, $methodName], $arguments);

        $this->dispatcher->dispatch(Events::PRE_TRANSFORM, new CollectionEvent($factCollection));

        foreach ($factCollection as $fact) {
            $this->dispatcher->dispatch(Events::TRANSFORM, new TransformEvent($fact));
        }

        $this->dispatcher->dispatch(Events::POST_TRANSFORM, new CollectionEvent($factCollection));

        return $factCollection;
    }

    /**
     * @param RepositoryInterface $repository
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }
}
