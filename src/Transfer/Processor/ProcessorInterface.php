<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Processor;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Transfer\Procedure\Procedure;

/**
 * Common processor interface.
 */
interface ProcessorInterface
{
    /**
     * Returns processor name.
     *
     * @return string Processor name
     */
    public function getName();

    /**
     * Adds a procedure to process queue.
     *
     * @param Procedure $procedure
     *
     * @return $this
     */
    public function addProcedure(Procedure $procedure);

    /**
     * Sets event dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher
     *
     * @return mixed
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher);

    /**
     * Adds an event subscriber.
     *
     * @param EventSubscriberInterface $subscriber The subscriber
     */
    public function addSubscriber(EventSubscriberInterface $subscriber);

    /**
     * Adds an event listener that listens on the specified event.
     *
     * @param string   $eventName The event to listen on
     * @param callable $listener  The listener
     * @param int      $priority  The higher the value, the earlier an event listener
     *                            will be triggered in the chain (defaults to 0)
     */
    public function addListener($eventName, $listener, $priority = 0);

    /**
     * Processes queued procedures.
     */
    public function process();
}
