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
use Transfer\Adapter\Transaction\Response;
use Transfer\Event\PostAdapterSendEvent;

class PostAdapterSendEventTest extends \PHPUnit_Framework_TestCase
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

        /** @var Response $response */
        $response = $this->getMockBuilder('Transfer\Adapter\Transaction\Response')->getMock();

        $event = new PostAdapterSendEvent($adapter, $request, $response, 10);

        $this->assertSame($adapter, $event->getOutputAdapter());
        $this->assertSame($request, $event->getRequest());
        $this->assertSame($response, $event->getResponse());
        $this->assertEquals(10, $event->getElapsedTime());
    }
}
