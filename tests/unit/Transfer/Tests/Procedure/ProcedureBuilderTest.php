<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Procedure;

use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Data\ObjectInterface;
use Transfer\Procedure\ProcedureBuilder;

class ProcedureBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests empty builder.
     */
    public function testEmptyBuilder()
    {
        $pb = new ProcedureBuilder();

        $procedure = $pb->getProcedure();

        $this->assertFalse($procedure->procedureExists(array('non_existing_procedure')));
    }

    /**
     * Tests simple procedure structure.
     */
    public function testSimpleProcedureStructure()
    {
        $pb = new ProcedureBuilder();

        $procedure = $pb->createProcedure('test_procedure')
                        ->end()
                        ->getProcedure();

        $this->assertTrue($procedure->procedureExists(array('test_procedure')));
        $this->assertTrue($procedure->procedureExists('test_procedure'));
    }

    /**
     * Tests multi-level procedure structure.
     */
    public function testMultiLevelProcedureStructure()
    {
        $pb = new ProcedureBuilder();

        $procedure = $pb
            ->createProcedure('first_level')
                ->createProcedure('second_level')
                    ->createProcedure('third_level')
                    ->end()
                ->end()
            ->end()
            ->getProcedure();

        $this->assertTrue($procedure->procedureExists(
            array('first_level', 'second_level', 'third_level')
        ));

        $this->assertFalse($procedure->procedureExists(array(
            array('first_level', 'third_level'),
        )));
    }

    /**
     * Tests insertion of callable components (sources, workers and targets).
     */
    public function testAddCallableComponent()
    {
        $pb = new ProcedureBuilder();

        $pb->addSource(function (Request $request) {
            return new Response();
        });

        $pb->addWorker(function (ObjectInterface $object) {
        });

        $pb->addTarget(function (Request $request) {
            return new Response();
        });

        $procedure = $pb->getProcedure();
        $sources = $procedure->getSources();
        $workers = $procedure->getWorkers();
        $targets = $procedure->getTargets();

        $this->assertCount(1, $sources);
        $this->assertInstanceOf('Transfer\Adapter\CallbackAdapter', $sources[0][0]);

        $this->assertCount(1, $workers);
        $this->assertInstanceOf('Transfer\Worker\WorkerInterface', $workers[0]);

        $this->assertCount(1, $targets);
        $this->assertInstanceOf('Transfer\Adapter\CallbackAdapter', $targets[0]);
    }
}
