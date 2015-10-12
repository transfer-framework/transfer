<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Storage;

use Transfer\Storage\StorageInterface;

class StorageTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Tests get(), set() and has() methods.
     */
    public function testGetSetHas()
    {
        $this->assertTrue($this->storage->add('value', 'key'));
        $this->assertTrue($this->storage->containsId('key'));
        $this->assertFalse($this->storage->containsId('non-existing-key'));

        $this->assertEquals('value', $this->storage->findById('key'));

        $this->setExpectedException('Transfer\Storage\Exception\ObjectNotFoundException');
        $this->assertNull($this->storage->findById('non-existing-key'));
    }

    /**
     * Tests all() method.
     */
    public function testAll()
    {
        $this->assertTrue($this->storage->add('value', 'key'));
        $this->assertCount(1, $this->storage->all());
        $this->assertTrue($this->storage->removeById('key'));
        $this->assertFalse($this->storage->containsId('key'));
        $this->assertCount(0, $this->storage->all());
    }
}
