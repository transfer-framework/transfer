<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Event;

use Transfer\Adapter\TargetAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Event\PreAdapterSendEvent;

class PreAdapterSendEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct.
     */
    public function testConstruct()
    {
        /** @var TargetAdapterInterface $adapter */
        $adapter = $this->getMockBuilder('Transfer\Adapter\TargetAdapterInterface')->getMock();

        /** @var Request $request */
        $request = $this->getMockBuilder('Transfer\Adapter\Transaction\Request')->getMock();

        $event = new PreAdapterSendEvent($adapter, $request);

        $this->assertSame($adapter, $event->getTarget());
        $this->assertSame($request, $event->getRequest());
    }
}
