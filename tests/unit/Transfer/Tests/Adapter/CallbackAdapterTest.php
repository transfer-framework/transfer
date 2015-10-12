<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter;

use Transfer\Adapter\CallbackAdapter;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;

class CallbackAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests adapter with valid callbacks.
     */
    public function testAdapter()
    {
        $adapter = new CallbackAdapter(
            function () {
                return new Response();
            },
            function () {
                return new Response();
            }
        );

        $response = $adapter->receive(new Request());

        $this->assertInstanceOf('Transfer\Adapter\Transaction\Response', $response);

        $response = $adapter->send(new Request());

        $this->assertInstanceOf('Transfer\Adapter\Transaction\Response', $response);
    }

    /**
     * Tests adapter with null arguments.
     */
    public function testAdapterWithNullArguments()
    {
        $adapter = new CallbackAdapter(null, null);

        $this->assertNull($adapter->receive(new Request()));
        $this->assertNull($adapter->send(new Request()));
    }
}
