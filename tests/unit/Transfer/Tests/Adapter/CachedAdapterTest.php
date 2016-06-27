<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter;

use Transfer\Adapter\CachedAdapter;
use Transfer\Adapter\CallbackAdapter;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Storage\InMemoryStorage;

class CachedAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests adapter.
     */
    public function testAdapter()
    {
        $adapter = new CachedAdapter(
            new InMemoryStorage(),
            new CallbackAdapter(
                function () { return new Response('test'); },
                function () { return new Response('test'); }
            )
        );

        $response = $adapter->receive(new Request());
        $this->assertEquals('test', $response->getData());
        $this->assertEquals(CachedAdapter::MISS, $response->getHeader('cache-status'));

        $response = $adapter->receive(new Request());
        $this->assertEquals('test', $response->getData());
        $this->assertEquals(CachedAdapter::HIT, $response->getHeader('cache-status'));
    }
}
