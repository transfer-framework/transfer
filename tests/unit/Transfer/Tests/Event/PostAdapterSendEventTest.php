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
use Transfer\Adapter\Transaction\Response;
use Transfer\Event\PostAdapterSendEvent;

class PostAdapterSendEventTest extends \PHPUnit_Framework_TestCase
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

        /** @var Response $response */
        $response = $this->getMockBuilder('Transfer\Adapter\Transaction\Response')->getMock();

        $event = new PostAdapterSendEvent($adapter, $request, $response, 10);

        $this->assertSame($adapter, $event->getTargetAdapter());
        $this->assertSame($request, $event->getRequest());
        $this->assertSame($response, $event->getResponse());
        $this->assertEquals(10, $event->getElapsedTime());
    }
}
