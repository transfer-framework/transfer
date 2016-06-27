<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter;

use Transfer\Adapter\SingleBufferAdapter;
use Transfer\Adapter\Transaction\Request;

class SingleBufferAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests adapter.
     */
    public function testAdapter()
    {
        $adapter = new SingleBufferAdapter();

        $response = $adapter->receive(new Request());
        $this->assertEquals(null, $response->getData());

        $response = $adapter->send(new Request('data'));

        $response = $adapter->receive(new Request());
        $this->assertEquals('data', $response->getData());

        $response = $adapter->send(new Request('new-data'));

        $response = $adapter->receive(new Request());
        $this->assertEquals('new-data', $response->getData());
    }
}
