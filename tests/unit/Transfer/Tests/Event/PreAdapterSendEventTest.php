<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Event;

use Transfer\Adapter\OutputAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Event\PreAdapterSendEvent;

class PreAdapterSendEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct.
     */
    public function testConstruct()
    {
        /** @var OutputAdapterInterface $adapter */
        $adapter = $this->getMockBuilder('Transfer\Adapter\OutputAdapterInterface')->getMock();

        /** @var Request $request */
        $request = $this->getMockBuilder('Transfer\Adapter\Transaction\Request')->getMock();

        $event = new PreAdapterSendEvent($adapter, $request);

        $this->assertSame($adapter, $event->getOutput());
        $this->assertSame($request, $event->getRequest());
    }
}
