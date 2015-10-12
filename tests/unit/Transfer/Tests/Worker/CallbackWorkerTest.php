<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Worker;

use Transfer\Data\ValueObject;
use Transfer\Worker\CallbackWorker;

class CallbackWorkerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CallbackWorker
     */
    private $worker;

    /**
     * Set up.
     */
    protected function setUp()
    {
        $this->worker = new CallbackWorker(function () {
            return new ValueObject('worked-object');
        });
    }

    /**
     * Tests handle method.
     */
    public function testHandle()
    {
        $this->assertEquals(new ValueObject('worked-object'), $this->worker->handle(new ValueObject(null)));
    }
}
