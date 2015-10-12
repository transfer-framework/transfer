<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Worker;

/**
 * Worker implementation with callback support.
 */
class CallbackWorker implements WorkerInterface
{
    /**
     * @var callback
     */
    protected $callback;

    /**
     * @param callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($object)
    {
        return call_user_func_array($this->callback, array($object));
    }
}
