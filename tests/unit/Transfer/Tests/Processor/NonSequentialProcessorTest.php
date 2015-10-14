<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Processor;

use Transfer\Adapter\CallbackAdapter;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Event\PreAdapterSendEvent;
use Transfer\Event\PreWorkerEvent;
use Transfer\Event\TransferEvents;
use Transfer\Procedure\Procedure;
use Transfer\Processor\NonSequentialProcessor;
use Transfer\Worker\CallbackWorker;

class NonSequentialProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NonSequentialProcessor
     */
    protected $processor;

    protected function setUp()
    {
        $this->processor = new NonSequentialProcessor();
    }

    /**
     * Tests getName method.
     */
    public function testProcessorName()
    {
        $this->assertEquals('Non-sequential processor', $this->processor->getName());
    }

    /**
     * Tests processing order.
     */
    public function testProcess()
    {
        $actual = array();

        /** @var object $procedureProphecy */
        $procedureProphecy = $this->prophesize('Transfer\Procedure\Procedure');
        $procedureProphecy->hasChildren()->willReturn(true);
        $procedureProphecy->getChildren()->willReturn(array());

        $procedureProphecy->getSources()->willReturn(array(
            array(
                new CallbackAdapter(
                    function () {
                        return new Response(array('a', 'b', 'c'));
                    }
                ),
                new Request(),
            ),
        ));

        $procedureProphecy->getWorkers()->willReturn(array(
            new CallbackWorker(function ($object) {
                return $object;
            }),
        ));

        $procedureProphecy->getTargets()->willReturn(array(
            new CallbackAdapter(
                null,
                function () {
                    return new Response();
                }
            ),
        ));

        /** @var Procedure $procedure */
        $procedure = $procedureProphecy->reveal();

        $this->processor->addListener(TransferEvents::PRE_WORKER, function (PreWorkerEvent $event) use (&$actual) {
            $actual[] = $event->getObject();
        });

        $this->processor->addListener(TransferEvents::PRE_ADAPTER_SEND, function (PreAdapterSendEvent $event) use (&$actual) {
            foreach ($event->getRequest()->getData() as $object) {
                $actual[] = $object;
            }
        });

        $this->processor->addProcedure($procedure);
        $this->processor->process();

        $this->assertEquals(array('a', 'b', 'c', 'a', 'b', 'c'), $actual);
    }
}
