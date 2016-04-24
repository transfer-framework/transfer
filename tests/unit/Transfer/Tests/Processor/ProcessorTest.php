<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Processor;

use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Transfer\Adapter\CallbackAdapter;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Data\ValueObject;
use Transfer\Event\PreAdapterSendEvent;
use Transfer\Event\TransferEvents;
use Transfer\Procedure\Procedure;
use Transfer\Procedure\ProcedureBuilder;
use Transfer\Processor\Processor;
use Transfer\Processor\SequentialProcessor;
use Transfer\Worker\CallbackWorker;

class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Processor
     */
    protected $processor;

    protected function setUp()
    {
        $this->processor = $this->getMockForAbstractClass('Transfer\Processor\Processor');
    }

    public function testSetStorageStack()
    {
        $this->assertSame(
            $this->processor,
            $this->processor->setStorageStack($this->getMock('Transfer\Storage\StorageStack'))
        );
    }

    public function testSetLogger()
    {
        $this->assertSame(
            $this->processor,
            $this->processor->setLogger($this->getMock('Psr\Log\LoggerInterface'))
        );
    }

    /**
     * Process with invalid source adapter.
     */
    public function testProcessWithInvalidSourceAdapter()
    {
        $this->setExpectedException('Transfer\Exception\MissingResponseException');

        $processor = new SequentialProcessor();

        /** @var object $procedureProphecy */
        $procedureProphecy = $this->prophesize('Transfer\Procedure\Procedure');
        $procedureProphecy->hasChildren()->willReturn(false);

        $procedureProphecy->getSources()->willReturn(array(
            array(
                new CallbackAdapter(
                    function () {

                    }
                ),
                new Request(),
            ),
        ));

        /** @var Procedure $procedure */
        $procedure = $procedureProphecy->reveal();

        $processor->addProcedure($procedure);
        $processor->process();
    }

    /**
     * Process with invalid target adapter.
     */
    public function testProcessWithInvalidTargetAdapter()
    {
        $this->setExpectedException('Transfer\Exception\MissingResponseException');

        $processor = new SequentialProcessor();

        /** @var object $procedureProphecy */
        $procedureProphecy = $this->prophesize('Transfer\Procedure\Procedure');
        $procedureProphecy->hasChildren()->willReturn(false);

        $procedureProphecy->getSources()->willReturn(array(
            array(
                new CallbackAdapter(
                    function () {
                        return new Response(array('a'));
                    }
                ),
                new Request(),
            ),
        ));

        $procedureProphecy->getWorkers()->willReturn(array());

        $procedureProphecy->getTargets()->willReturn(array(
            new CallbackAdapter(
                null,
                function () {

                }
            ),
        ));

        /** @var Procedure $procedure */
        $procedure = $procedureProphecy->reveal();

        $processor->addProcedure($procedure);
        $processor->process();
    }

    public function testObjectRemovalAndModificationInWorkerStage()
    {
        $actual = array();

        $processor = new SequentialProcessor();

        /** @var object $procedureProphecy */
        $procedureProphecy = $this->prophesize('Transfer\Procedure\Procedure');
        $procedureProphecy->hasChildren()->willReturn(false);

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
                if ($object == 'a') {
                    return;
                }

                if ($object == 'b') {
                    return 'd';
                }

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

        $processor->addListener(TransferEvents::PRE_ADAPTER_SEND, function (PreAdapterSendEvent $event) use (&$actual) {
            foreach ($event->getRequest()->getData() as $object) {
                $actual[] = $object;
            }
        });

        /** @var Procedure $procedure */
        $procedure = $procedureProphecy->reveal();

        $processor->addProcedure($procedure);
        $processor->process();

        $this->assertEquals(array('d', 'c'), $actual);
    }

    public function testDependencyInjection()
    {
        $processor = new SequentialProcessor();

        /** @var object $procedureProphecy */
        $procedureProphecy = $this->prophesize('Transfer\Procedure\Procedure');
        $procedureProphecy->hasChildren()->willReturn(false);

        $procedureProphecy->getName()->willReturn('test');

        $procedureProphecy->getSources()->willReturn(array(
            array(
                new CallbackAdapter(
                    function () {
                        return new Response(array(new ValueObject('a')));
                    }
                ),
                new Request(),
            ),
        ));

        /** @var object $workerProphecy */
        $workerProphecy = $this->prophesize('Transfer\Worker\WorkerInterface');
        $workerProphecy->willImplement('Transfer\Storage\StorageStackAwareInterface');
        $workerProphecy->willImplement('Psr\Log\LoggerAwareInterface');

        $workerProphecy->setStorageStack(Argument::type('Transfer\Storage\StorageStack'))->willReturn(null);
        $workerProphecy->setLogger(Argument::type('Psr\Log\LoggerInterface'))->willReturn(null);
        $workerProphecy->handle(Argument::any())->will(function ($args) {
            return $args[0];
        });

        $procedureProphecy->getWorkers()->willReturn(array(
            $workerProphecy->reveal(),
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
        $processor->addProcedure($procedure);

        $loggerProphecy = $this->prophesize('Psr\Log\LoggerInterface');
        $loggerProphecy->info(Argument::type('string'))->shouldBeCalled();

        /** @var LoggerInterface $logger */
        $logger = $loggerProphecy->reveal();
        $processor->setLogger($logger);

        $processor->process();
    }

    public function testWorkerChaining()
    {
        $actual = array();

        /** @var object $procedureProphecy */
        $procedureProphecy = $this->prophesize('Transfer\Procedure\Procedure');
        $procedureProphecy->hasChildren()->willReturn(false);

        $procedureProphecy->getSources()->willReturn(array(
            array(
                new CallbackAdapter(
                    function () {
                        return new Response(array('1', '2', '3'));
                    }
                ),
                new Request(),
            ),
        ));

        $procedureProphecy->getWorkers()->willReturn(array(
            new CallbackWorker(function ($object) {
                return $object.'X';
            }),
            new CallbackWorker(function ($object) {
                return $object.'X';
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

        $procedure = $procedureProphecy->reveal();

        $processor = new SequentialProcessor();
        $processor->addProcedure($procedure);

        $processor->addListener(TransferEvents::PRE_ADAPTER_SEND, function (PreAdapterSendEvent $event) use (&$actual) {
            foreach ($event->getRequest()->getData() as $object) {
                $actual[] = $object;
            }
        });

        $processor->process();

        $this->assertEquals(array('1XX', '2XX', '3XX'), $actual);
    }

    public function testObjectSplitting()
    {
        $builder = new ProcedureBuilder();

        $expected = array('f', 'o', 'x');

        $actual = array();

        $builder
            ->addSource(function () {
                return new Response(array(array(array('f'), array('o'), array('x'))));
            })
            ->split()
            ->split()
            ->addWorker(function ($object) use (&$actual) {
                $actual[] = $object;
            })
            ->end();

        $processor = new SequentialProcessor();

        $processor
            ->addProcedure($builder->getProcedure())
            ->process();

        $this->assertEquals($expected, $actual);
    }
}
