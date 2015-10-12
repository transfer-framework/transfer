<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Symfony\Component\EventDispatcher\Event;
use Transfer\Worker\WorkerInterface;

/**
 * Event triggered before an object is passed through a worker.
 */
class PreWorkerEvent extends Event
{
    /**
     * @var WorkerInterface Worker
     */
    protected $worker;

    /**
     * @var mixed Object sent to worker
     */
    protected $object;

    /**
     * @param WorkerInterface $worker Worker
     * @param mixed           $object Object sent to worker
     */
    public function __construct(WorkerInterface $worker, $object)
    {
        $this->worker = $worker;
        $this->object = $object;
    }

    /**
     * Returns worker.
     *
     * @return WorkerInterface Worker
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Returns object which is going to be passed through a worker.
     *
     * @return mixed Object sent to worker
     */
    public function getObject()
    {
        return $this->object;
    }
}
