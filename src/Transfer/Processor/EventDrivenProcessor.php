<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Processor;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Transfer\Adapter\InputAdapterInterface;
use Transfer\Adapter\OutputAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Event as Events;
use Transfer\Event\TransferEvents;
use Transfer\Procedure\Procedure;
use Transfer\Processor\EventSubscriber\Logger;
use Transfer\Storage\StorageInterface;
use Transfer\Worker\WorkerInterface;

/**
 * Partial processor implementation with event functionality.
 */
abstract class EventDrivenProcessor extends Processor
{
    /**
     * @var EventDispatcherInterface Event dispatcher
     */
    protected $dispatcher;

    public function __construct()
    {
        parent::__construct();

        $this->dispatcher = new EventDispatcher();
    }

    /**
     * {@inheritdoc}
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->dispatcher->addSubscriber($subscriber);
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        parent::setLogger($logger);

        $this->addSubscriber(new Logger($logger));
    }

    /**
     * {@inheritdoc}
     */
    public function process()
    {
        $this->dispatcher->dispatch(TransferEvents::PRE_PROCESS, new Events\PreProcessEvent($this));

        parent::process();

        $this->dispatcher->dispatch(TransferEvents::POST_PROCESS, new Events\PostProcessEvent($this));
    }

    /**
     * {@inheritdoc}
     */
    protected function handleProcedureOuter(Procedure $procedure)
    {
        $this->dispatcher->dispatch(TransferEvents::PRE_PROCEDURE, new Events\PreProcedureEvent($procedure));

        parent::handleProcedureOuter($procedure);

        $this->dispatcher->dispatch(TransferEvents::POST_PROCEDURE, new Events\PostProcedureEvent($procedure, $this));
    }

    /**
     * {@inheritdoc}
     */
    protected function handleInput(InputAdapterInterface $adapter, Request $request)
    {
        $this->dispatcher->dispatch(TransferEvents::PRE_ADAPTER_RECEIVE, new Events\PreAdapterReceiveEvent($adapter, $request));

        $response = parent::handleInput($adapter, $request);

        $this->dispatcher->dispatch(
            TransferEvents::POST_ADAPTER_RECEIVE,
            new Events\PostAdapterReceiveEvent($adapter, $response, 0.0)
        );

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected function handleWorker(WorkerInterface $worker, $object, StorageInterface $storage)
    {
        $this->dispatcher->dispatch(TransferEvents::PRE_WORKER, new Events\PreWorkerEvent($worker, $object));

        parent::handleWorker($worker, $object, $storage);

        $this->dispatcher->dispatch(TransferEvents::POST_WORKER, new Events\PostWorkerEvent($worker, $object, 0.0));
    }

    /**
     * {@inheritdoc}
     */
    protected function handleOutput(OutputAdapterInterface $adapter, Request $request)
    {
        $this->dispatcher->dispatch(TransferEvents::PRE_ADAPTER_SEND, new Events\PreAdapterSendEvent($adapter, $request));

        $response = parent::handleOutput($adapter, $request);

        $this->dispatcher->dispatch(TransferEvents::POST_ADAPTER_SEND, new Events\PostAdapterSendEvent(
            $adapter, $request, $response, 0.0
        ));

        return $response;
    }
}
