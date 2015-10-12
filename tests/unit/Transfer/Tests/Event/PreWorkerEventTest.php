<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Event;

use Transfer\Event\PreWorkerEvent;
use Transfer\Data\ValueObject;
use Transfer\Worker\WorkerInterface;

class PreWorkerEventTest extends \PHPUnit_Framework_TestCase
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

        $event = new PreWorkerEvent($worker, $object);

        $this->assertSame($worker, $event->getWorker());
        $this->assertSame($object, $event->getObject());
    }
}
