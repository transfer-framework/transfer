<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Processor;

use Prophecy\Argument;
use Psr\Log\NullLogger;
use Transfer\Adapter\CallbackAdapter;
use Transfer\Adapter\Transaction\Response;
use Transfer\Data\ValueObject;
use Transfer\Event\PreAdapterSendEvent;
use Transfer\Event\PreWorkerEvent;
use Transfer\Event\TransferEvents;
use Transfer\Procedure\ProcedureBuilder;
use Transfer\Processor\EventDrivenProcessor;
use Transfer\Processor\NonSequentialProcessor;
use Transfer\Processor\SequentialProcessor;
use Transfer\Storage\InMemoryStorage;
use Transfer\Storage\StorageStack;

class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests getName method.
     */
    public function testNonSequentialProcessorName()
    {
        $processor = new NonSequentialProcessor();

        $this->assertEquals('Non-sequential processor', $processor->getName());
    }

    /**
     * Tests getName method.
     */
    public function testSequentialProcessorName()
    {
        $processor = new SequentialProcessor();

        $this->assertEquals('Sequential processor', $processor->getName());
    }

    /**
     * Tests sequential processor execution order.
     */
    public function testSequentialProcessorExecutionOrder()
    {
        $processor = new NonSequentialProcessor();

        $this->assertOrder($processor, array('a', 'b', 'c', 'a', 'b', 'c'));
    }

    /**
     * Tests non-sequential processor execution order.
     */
    public function testNonSequentialProcessorExecutionOrder()
    {
        $processor = new SequentialProcessor();

        $this->assertOrder($processor, array('a', 'a', 'b', 'b', 'c', 'c'));
    }

    /**
     * @param EventDrivenProcessor $processor
     * @param array                $expected
     */
    private function assertOrder($processor, $expected)
    {
        $workerProphecy = $this->prophesize('Transfer\Worker\WorkerInterface');
        $workerProphecy->willImplement('Transfer\Storage\StorageStackAwareInterface');
        $workerProphecy->willImplement('Psr\Log\LoggerAwareInterface');

        $workerProphecy->setStorageStack(Argument::type('Transfer\Storage\StorageStack'))->willReturn(null);
        $workerProphecy->setLogger(Argument::type('Psr\Log\LoggerInterface'))->willReturn(null);
        $workerProphecy->handle(Argument::any())->will(function ($args) {
            return $args[0];
        });

        $worker = $workerProphecy->reveal();

        $pb = new ProcedureBuilder();
        $procedure = $pb
            ->createProcedure('test-procedure')
                ->addInput(new CallbackAdapter(
                    function () {
                        return new Response(new \ArrayIterator(array(
                            new ValueObject('a'),
                            new ValueObject('b'),
                            new ValueObject('c'),
                        )));
                    },
                    function () {}
                ))
                ->addWorker($worker)
                ->addOutput(new CallbackAdapter(function () {}, function () { return new Response(); }))
            ->end()
            ->getProcedure();

        $processor->addListener(TransferEvents::PRE_WORKER, function (PreWorkerEvent $event) use (&$actual) {
            $actual[] = $event->getObject()->data;
        });

        $processor->addListener(TransferEvents::PRE_ADAPTER_SEND, function (PreAdapterSendEvent $event) use (&$actual) {
            foreach ($event->getRequest()->getData() as $object) {
                $actual[] = $object->data;
            }
        });

        $processor->setStorageStack(new StorageStack(array('global' => new InMemoryStorage())));
        $processor->setLogger(new NullLogger());

        $processor->addProcedure($procedure);
        $processor->process();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Process with invalid input adapter.
     */
    public function testProcessWithInvalidInputAdapter()
    {
        $processor = new SequentialProcessor();

        $pb = new ProcedureBuilder();

        $procedure = $pb
            ->createProcedure('test-procedure')
                ->addInput(new CallbackAdapter(function () {}, function () {}))
            ->end()
            ->getProcedure();

        $processor->addProcedure($procedure);

        $this->setExpectedException('Transfer\Exception\MissingResponseException');

        $processor->process();
    }

    /**
     * Process with invalid output adapter.
     */
    public function testProcessWithInvalidOutputAdapter()
    {
        $processor = new SequentialProcessor();

        $pb = new ProcedureBuilder();

        $procedure = $pb
            ->createProcedure('test-procedure')
                ->addInput(new CallbackAdapter(
                    function () {
                        $response = new Response();
                        $response->setData(new \ArrayIterator(array(
                            new ValueObject(null),
                        )));

                        return $response;
                    },
                    function () {
                        return new Response();
                    }
                ))
                ->addOutput(new CallbackAdapter(function () {}, function () {}))
            ->end()
            ->getProcedure();

        $processor->addProcedure($procedure);

        $this->setExpectedException('Transfer\Exception\MissingResponseException');

        $processor->process();
    }
}
