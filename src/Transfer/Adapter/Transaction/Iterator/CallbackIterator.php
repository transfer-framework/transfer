<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter\Transaction\Iterator;

/**
 * Iterator implementation with callback support.
 */
class CallbackIterator implements \Iterator
{
    /**
     * @var callback Callback for returning current element
     */
    protected $current;

    /**
     * @var callback Callback for validating next element
     */
    protected $valid;

    /**
     * @var int Iteration
     */
    protected $iteration = 0;

    /**
     * @param callback $valid   Callback for validating next element
     * @param callback $current Callback for returning current element
     */
    public function __construct($valid, $current)
    {
        $this->valid = $valid;
        $this->current = $current;
    }

    /**
     * Moves internal pointer to next position.
     */
    public function next()
    {
        ++$this->iteration;
    }

    /**
     * Returns current element.
     *
     * @return mixed Current element
     */
    public function current()
    {
        return call_user_func_array($this->current, array($this));
    }

    /**
     * Returns current position.
     *
     * @return int Current position
     */
    public function key()
    {
        return $this->iteration;
    }

    /**
     * Tests, if next element is valid.
     *
     * @return bool True, if valid
     */
    public function valid()
    {
        return (boolean) call_user_func_array($this->valid, array($this));
    }

    /**
     * Sets internal pointer to initial position.
     */
    public function rewind()
    {
        $this->iteration = 0;
    }
}
