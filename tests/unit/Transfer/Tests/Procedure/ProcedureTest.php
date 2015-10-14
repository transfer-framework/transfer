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
     * Tests getTargets method.
     */
    public function testGetTargets()
    {
        $pb = new ProcedureBuilder();

        $adapter = $this->getMock('Transfer\Adapter\TargetAdapterInterface');

        $pb
            ->addTarget($adapter)
            ->createProcedure('procedure_1')
                ->addTarget($adapter)
            ->end();

        $procedure = $pb->getProcedure();

        $children = $procedure->getChildren();

        /** @var Procedure $child */
        foreach ($children as $child) {
            $targets = $child->getTargets();
            $this->assertCount(2, $targets);
        }
    }
}
