<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Processor\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Transfer\Event as Events;
use Transfer\Event\TransferEvents;

/**
 * Listens to and logs processor events.
 */
class ActivitySubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            TransferEvents::PRE_PROCESS => array('logPreProcessEvent', 0),
            TransferEvents::POST_PROCESS => array('logPostProcessEvent', 0),
        );
    }

    /**
     * Logs pre process event.
     */
    public function logPreProcessEvent()
    {
        $this->writeToStream(array(
            'type' => 'event',
            'event' => 'pre_process',
        ));

        $this->writeToStream(array(
            'type' => 'metric',
            'metric' => 'memory_usage',
            'value' => round(memory_get_usage() / 1024 / 1024),
        ));
    }

    /**
     * Logs post process event.
     */
    public function logPostProcessEvent()
    {
        $this->writeToStream(array(
            'type' => 'event',
            'event' => 'post_process',
        ));

        $this->writeToStream(array(
            'type' => 'metric',
            'metric' => 'peak_memory_usage',
            'value' => round(memory_get_peak_usage() / 1024 / 1024),
        ));
    }

    /**
     * Writes to output stream.
     *
     * @param array $data
     */
    private function writeToStream(array $data)
    {
        fwrite(STDOUT, json_encode($data)."\n");
    }
}
