<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Event;

use Transfer\Event\PreProcessEvent;
use Transfer\Processor\ProcessorInterface;

class PreProcessEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct.
     */
    public function testConstruct()
    {
        /** @var ProcessorInterface $processor */
        $processor = $this->getMockBuilder('Transfer\Processor\ProcessorInterface')->getMock();

        $event = new PreProcessEvent($processor);

        $this->assertSame($processor, $event->getProcessor());
    }
}
