<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Transfer\Worker\WorkerInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event triggered after an object is passed through a worker.
 */
class PostWorkerEvent extends Event
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
     * @var float Elapsed time
     */
    protected $elapsedTime;

    /**
     * @param WorkerInterface $worker      Worker
     * @param mixed           $object      Object sent to worker
     * @param float           $elapsedTime Elapsed time
     */
    public function __construct(WorkerInterface $worker, $object, $elapsedTime = 0.0)
    {
        $this->worker = $worker;
        $this->object = $object;
        $this->elapsedTime = $elapsedTime;
    }

    /**
     * Returns worker.
     *
     * @return WorkerInterface
     */
    public function getWorker()
    {
        return $this->worker;
    }

    /**
     * Returns object which was sent to worker.
     *
     * @return mixed Object sent to worker
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Returns elapsed time.
     *
     * @return float Elapsed time
     */
    public function getElapsedTime()
    {
        return $this->elapsedTime;
    }
}
