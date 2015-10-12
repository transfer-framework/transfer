<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Event;

use Transfer\Event\PostProcedureEvent;
use Transfer\Procedure\Procedure;
use Transfer\Processor\ProcessorInterface;

class PostProcedureEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct.
     */
    public function testConstruct()
    {
        /** @var Procedure $procedure */
        $procedure = $this->getMockBuilder('Transfer\Procedure\Procedure')->getMock();

        /** @var ProcessorInterface $processor */
        $processor = $this->getMockBuilder('Transfer\Processor\ProcessorInterface')->getMock();

        $event = new PostProcedureEvent($procedure, $processor);

        $this->assertSame($procedure, $event->getProcedure());
        $this->assertSame($processor, $event->getProcessor());
    }
}
