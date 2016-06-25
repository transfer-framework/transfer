<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter\Transaction;

/**
 * Holds data for transaction objects.
 */
class DataAggregate implements \IteratorAggregate
{
    /**
     * @var \Iterator Data iterator
     */
    protected $iterator;

    /**
     * @var mixed Original data
     */
    protected $originalData;

    /**
     * @param mixed $data Original data
     */
    public function __construct($data)
    {
        $this->setData($data);
    }

    /**
     * Returns original data.
     *
     * @return mixed Original data
     */
    public function getData()
    {
        return $this->originalData;
    }

    /**
     * Assigns data to the aggregate.
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->originalData = $data;

        if ($data instanceof \Iterator) {
            $this->iterator = $data;
        }
        elseif (is_array($data)) {
            $this->iterator = new \ArrayIterator($data);
        }
        else {
            $this->iterator = new \ArrayIterator(array($data));
        }
    }

    /**
     * Returns iterator with associated data.
     *
     * If the data aggregate was initialized with an array, an \ArrayIterator will be returned.
     *
     * @return \Iterator Data iterator
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
