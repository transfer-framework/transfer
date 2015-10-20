<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter;

use Transfer\Adapter\Transaction\Request;

class NonDirectionalAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests handle method.
     */
    public function testAdapter()
    {
        $adapter = $this->getMockForAbstractClass('Transfer\Adapter\NonDirectionalAdapter');

        $this->assertNull($adapter->receive(new Request()));
        $this->assertNull($adapter->send(new Request()));
    }
}
