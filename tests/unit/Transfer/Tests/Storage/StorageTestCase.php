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
     * Tests basic operations.
     */
    public function testBasicOperations()
    {
        $this->assertTrue($this->storage->add('value', 'id'));
        $this->assertTrue($this->storage->containsId('id'));

        $this->assertEquals('value', $this->storage->findById('id'));

        $this->storage->add('another-value');
        $this->assertTrue($this->storage->contains('another-value'));

        $this->assertTrue($this->storage->remove('another-value'));
        $this->assertFalse($this->storage->contains('another-value'));
    }

    /**
     * Tests storage search.
     */
    public function testFindByIdOnNonExistingId()
    {
        $this->setExpectedException('Transfer\Storage\Exception\ObjectNotFoundException');
        $this->assertNull($this->storage->findById('non-existing-id'));
    }

    /**
     * Tests all() method.
     */
    public function testAll()
    {
        $this->assertTrue($this->storage->add('value', 'id'));
        $this->assertCount(1, $this->storage->all());
        $this->assertTrue($this->storage->removeById('id'));
        $this->assertFalse($this->storage->containsId('id'));
        $this->assertCount(0, $this->storage->all());
    }
}
