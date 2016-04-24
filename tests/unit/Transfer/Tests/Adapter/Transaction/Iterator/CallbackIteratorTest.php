<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter\Transaction\Iterator;

use Transfer\Adapter\Transaction\Iterator\CallbackIterator;
use Transfer\Data\ValueObject;

class CallbackIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests callbacks.
     */
    public function testCallback()
    {
        $stack = array(
            new ValueObject(array('key1' => 'value1')),
            new ValueObject(array('key2' => 'value2')),
            new ValueObject(array('key3' => 'value3')),
        );

        $iterator = new CallbackIterator(
            function (CallbackIterator $stream) use ($stack) {
                return isset($stack[$stream->key()]);
            },
            function (CallbackIterator $stream) use ($stack) {
                return $stack[$stream->key()];
            }
        );

        foreach ($iterator as $index => $object) {
            $this->assertEquals($stack[$index], $object);
        }
    }
}
