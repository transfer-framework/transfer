<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Worker;

use Transfer\Storage\InMemoryStorage;
use Transfer\Storage\StorageStack;
use Transfer\Worker\SplitterWorker;

class SplitterWorkerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SplitterWorker
     */
    private $worker;

    /**
     * @var InMemoryStorage
     */
    private $storage;

    /**
     * Set up.
     */
    protected function setUp()
    {
        $this->worker = new SplitterWorker(function ($object) {
            return $object;
        });

        $this->storage = new InMemoryStorage();

        $stack = new StorageStack(array(
            'local' => $this->storage,
        ));

        $this->worker->setStorageStack($stack);
    }

    /**
     * Tests handle method.
     */
    public function testHandle()
    {
        $objects = array(
            '8fa14cdd754f91cc6554c9e71929cce7' => 'f',
            'd95679752134a2d9eb61dbd7b91c4bcc' => 'o',
            '9dd4e461268c8034f5c8564e155c67a6' => 'x',
        );

        $this->worker->handle($objects);

        $this->assertEquals($objects, $this->storage->all());
    }

    public function testHandleWithInvalidArgument()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $this->worker->handle(null);
    }
}
