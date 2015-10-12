<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Procedure;

use Transfer\Procedure\Procedure;
use Transfer\Procedure\ProcedureBuilder;

class ProcedureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests getOutputs method.
     */
    public function testGetOutputs()
    {
        $pb = new ProcedureBuilder();

        $adapter = $this->getMock('Transfer\Adapter\OutputAdapterInterface');

        $pb
            ->addOutput($adapter)
            ->createProcedure('procedure_1')
                ->addOutput($adapter)
            ->end();

        $procedure = $pb->getProcedure();

        $children = $procedure->getChildren();

        /** @var Procedure $child */
        foreach ($children as $child) {
            $outputs = $child->getOutputs();
            $this->assertCount(2, $outputs);
        }
    }
}
