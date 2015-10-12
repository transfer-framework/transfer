<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Worker;

/**
 * Common worker interface.
 */
interface WorkerInterface
{
    /**
     * Lets the worker do operations on the object.
     *
     * @param mixed $object Original object
     */
    public function handle($object);
}
