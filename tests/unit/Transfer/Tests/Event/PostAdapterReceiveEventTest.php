<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Event;

use Transfer\Adapter\InputAdapterInterface;
use Transfer\Adapter\Transaction\Response;
use Transfer\Event\PostAdapterReceiveEvent;

class PostAdapterReceiveEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct.
     */
    public function testConstruct()
    {
        /** @var InputAdapterInterface $adapter */
        $adapter = $this->getMockBuilder('Transfer\Adapter\InputAdapterInterface')->getMock();

        /** @var Response $response */
        $response = $this->getMockBuilder('Transfer\Adapter\Transaction\Response')->getMock();

        $event = new PostAdapterReceiveEvent($adapter, $response, 10);

        $this->assertSame($adapter, $event->getInputAdapter());
        $this->assertSame($response, $event->getResponse());
        $this->assertEquals(10, $event->getElapsedTime());
    }
}
