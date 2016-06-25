<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter\Transaction;

use Transfer\Adapter\Transaction\DataAggregate;

class DataAggregateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct with valid argument.
     */
    public function testConstructWithValidArgument()
    {
        $aggregate = new DataAggregate(array());
        $this->assertInstanceOf('\ArrayIterator', $aggregate->getIterator());
        $this->assertInternalType('array', $aggregate->getData());

        $aggregate = new DataAggregate(new \ArrayIterator());
        $this->assertInstanceOf('\ArrayIterator', $aggregate->getIterator());
        $this->assertInstanceOf('\ArrayIterator', $aggregate->getData());

        $aggregate = new DataAggregate('string');
        $this->assertInstanceOf('\ArrayIterator', $aggregate->getIterator());
        $this->assertInternalType('string', $aggregate->getData());
    }
}
