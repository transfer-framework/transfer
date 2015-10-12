<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Event;

use Transfer\Data\ValueObject;
use Transfer\Event\PostWorkerEvent;
use Transfer\Worker\WorkerInterface;

class PostWorkerEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct.
     */
    public function testConstruct()
    {
        /** @var WorkerInterface $worker */
        $worker = $this->getMockBuilder('Transfer\Worker\WorkerInterface')->getMock();

        /** @var ValueObject $object */
        $object = $this->getMockBuilder('Transfer\Data\ValueObject')->disableOriginalConstructor()->getMock();

        $event = new PostWorkerevent($worker, $object, 10);

        $this->assertSame($worker, $event->getWorker());
        $this->assertSame($object, $event->getObject());
        $this->assertEquals(10, $event->getElapsedTime());
    }
}
