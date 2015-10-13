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
     * @param array|\Iterator|\Traversable $data Data array or iterator
     */
    public function __construct($data)
    {
        $this->setData($data);
    }

    /**
     * Returns iterator with associated data.
     *
     * If the data aggregate was initialized with an array, an \ArrayIterator will be returned.
     *
     * @return \Iterator Data iterator
     */
    public function getData()
    {
        return $this->iterator;
    }

    /**
     * Assigns data to the aggregate.
     *
     * @param array|\Iterator $object
     */
    public function setData($object)
    {
        if ($object instanceof \Iterator) {
            $this->iterator = $object;
        } elseif (is_array($object)) {
            $this->iterator = new \ArrayIterator($object);
        } else {
            throw new \InvalidArgumentException(sprintf(
                'Expecting object of type array or \Iterator, %s given',
                gettype($object)
            ));
        }
    }

    /**
     * @see self::getData()
     */
    public function getIterator()
    {
        return $this->iterator;
    }
}
