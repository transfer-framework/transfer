<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Storage;

use Transfer\Storage\StorageInterface;
use Transfer\Storage\StorageStack;

class StorageStackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        $stack = new StorageStack(array(
            'local' => $this->getMock('Transfer\Storage\StorageInterface'),
            'global' => $this->getMock('Transfer\Storage\StorageInterface'),
        ));

        $this->assertCount(2, $stack->getScopes());
    }

    /**
     * Tests set and get methods.
     */
    public function testSetGetScope()
    {
        $stack = new StorageStack();

        $this->assertCount(0, $stack->getScopes());

        /** @var StorageInterface $storage */
        $storage = $this->getMock('Transfer\Storage\StorageInterface');

        $stack->setScope('local', $storage);

        $this->assertCount(1, $stack->getScopes());
        $this->assertSame($storage, $stack->getScope('local'));
    }

    /**
     * Tests remove method.
     */
    public function testRemoveScope()
    {
        $stack = new StorageStack(array(
            'local' => $this->getMock('Transfer\Storage\StorageInterface'),
            'global' => $this->getMock('Transfer\Storage\StorageInterface'),
        ));

        $stack->removeScope('local');

        $this->assertCount(1, $stack->getScopes());
    }
}
