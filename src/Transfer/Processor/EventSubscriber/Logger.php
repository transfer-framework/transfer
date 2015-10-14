<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Processor\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Transfer\Event as Events;
use Transfer\Event\TransferEvents;

/**
 * Listens to and logs processor events.
 */
class Logger implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface Logger
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger Logger to use
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            TransferEvents::PRE_PROCESS => array('logPreProcessEvent', 0),
            TransferEvents::POST_PROCESS => array('logPostProcessEvent', 0),
            TransferEvents::PRE_PROCEDURE => array('logPreProcedureEvent', 0),
            TransferEvents::POST_PROCEDURE => array('logPostProcedureEvent', 0),
            TransferEvents::PRE_ADAPTER_RECEIVE => array('logPreAdapterReceiveEvent', 0),
            TransferEvents::POST_ADAPTER_RECEIVE => array('logPostAdapterReceiveEvent', 0),
            TransferEvents::PRE_WORKER => array('logPreWorkerEvent', 0),
            TransferEvents::POST_WORKER => array('logPostWorkerEvent', 0),
            TransferEvents::PRE_ADAPTER_SEND => array('logPreAdapterSendEvent', 0),
            TransferEvents::POST_ADAPTER_SEND => array('logPostAdapterSendEvent', 0),
        );
    }

    /**
     * Logs pre process event.
     */
    public function logPreProcessEvent()
    {
        $this->logger->info('Starting processing procedures...');
    }

    /**
     * Logs post process event.
     */
    public function logPostProcessEvent()
    {
        $this->logger->info('Finished processing all procedures!');
    }

    /**
     * Logs pre procedure event.
     *
     * @param Events\PreProcedureEvent $event
     */
    public function logPreProcedureEvent(Events\PreProcedureEvent $event)
    {
        $procedure = $event->getProcedure();

        $this->logger->info(sprintf('Starting procedure "%s"...', $procedure->getName()));
    }

    /**
     * Logs post procedure event.
     *
     * @param Events\PostProcedureEvent $event
     */
    public function logPostProcedureEvent(Events\PostProcedureEvent $event)
    {
        $procedure = $event->getProcedure();

        $this->logger->info(sprintf('Finished procedure "%s"', $procedure->getName()));
    }

    /**
     * Logs pre adapter receive event.
     */
    public function logPreAdapterReceiveEvent()
    {
        $this->logger->info('Receiving data from adapter...');
    }

    /**
     * Logs post adapter receive.
     *
     * @param Events\PostAdapterReceiveEvent $event
     */
    public function logPostAdapterReceiveEvent(Events\PostAdapterReceiveEvent $event)
    {
        $source = $event->getSourceAdapter();

        $this->logger->info(sprintf('Received data from adapter "%s"', get_class($source)));
    }

    /**
     * Logs pre worker event.
     */
    public function logPreWorkerEvent()
    {
        $this->logger->info('Working object...');
    }

    /**
     * Logs post worker event.
     *
     * @param Events\PostWorkerEvent $event
     */
    public function logPostWorkerEvent(Events\PostWorkerEvent $event)
    {
        $worker = $event->getWorker();

        $this->logger->info(sprintf('Worked an object with "%s"', get_class($worker)));
    }

    /**
     * Logs pre adapter send event.
     */
    public function logPreAdapterSendEvent()
    {
        $this->logger->info('Sending data to adapter...');
    }

    /**
     * Logs post adapter send event.
     *
     * @param Events\PostAdapterSendEvent $event
     */
    public function logPostAdapterSendEvent(Events\PostAdapterSendEvent $event)
    {
        $target = $event->getTargetAdapter();

        $this->logger->info(sprintf('Objects sent to adapter "%s"', get_class($target)));
    }
}
