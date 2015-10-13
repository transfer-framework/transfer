<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Storage;

use Transfer\Storage\ChainStorage;
use Transfer\Storage\Exception\ObjectNotFoundException;
use Transfer\Storage\InMemoryStorage;
use Transfer\Storage\StorageInterface;

class ChainStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct.
     */
    public function testConstruct()
    {
        $this->setExpectedException('\InvalidArgumentException');

        new ChainStorage(array(
            new InMemoryStorage(),
            null,
        ));
    }

    /**
     * Tests set, has and get methods with valid storage.
     */
    public function testSetHasGetWithValidStorage()
    {
        $storage = new ChainStorage(array(
            $this->getValidArrayStorageStub(),
            $this->getValidIteratorStorageStub(),
        ));

        $this->assertTrue($storage->add('value', 'key'));
        $this->assertTrue($storage->containsId('key'));
        $this->assertTrue($storage->contains('value'));
        $this->assertEquals('value', $storage->findById('key'));
        $this->assertTrue($storage->remove('value'));
        $this->assertTrue($storage->removeById('key'));
    }

    /**
     * Tests all method with valid storage.
     */
    public function testAllWithValidStorage()
    {
        $storage = new ChainStorage(array(
            $this->getValidArrayStorageStub(),
            $this->getValidIteratorStorageStub(),
        ));

        $this->assertInstanceOf('\Iterator', $storage->all());
    }

    /**
     * Tests set, has and get methods with bad storage.
     */
    public function testSetHasGetWithBadStorage()
    {
        $storage = new ChainStorage(array(
            $this->getBadStorageStub(),
        ));

        $this->assertFalse($storage->add('value', 'key'));
        $this->assertFalse($storage->containsId('key'));
        $this->assertFalse($storage->contains('key'));
        $this->assertFalse($storage->remove('value'));
        $this->assertFalse($storage->removeById('key'));

        $this->setExpectedException('Transfer\Storage\Exception\ObjectNotFoundException');
        $this->assertEquals(null, $storage->findById('key'));
    }

    /**
     * Tests all method with bad storage.
     */
    public function testAllWithBadStorage()
    {
        $storage = new ChainStorage(array(
            $this->getBadStorageStub(),
        ));

        $this->setExpectedException('Transfer\Storage\Exception\BadStorageIterableException');
        $this->assertEquals(null, $storage->all());
    }

    /**
     * @return StorageInterface
     */
    private function getValidArrayStorageStub()
    {
        /** @var object $stub */
        $stub = $this->getValidStorageStub();

        $stub->method('all')
             ->willReturn(array());

        return $stub;
    }

    /**
     * @return StorageInterface
     */
    private function getValidIteratorStorageStub()
    {
        /** @var object $stub */
        $stub = $this->getValidStorageStub();

        $stub->method('all')
             ->willReturn(new \ArrayIterator());

        return $stub;
    }

    private function getValidStorageStub()
    {
        /** @var object $stub */
        $stub = $this->getMockBuilder('Transfer\Storage\StorageInterface')
            ->getMock();

        $stub->method('add')
             ->willReturn(true);

        $stub->method('findById')
             ->willReturn('value');

        $stub->method('containsId')
             ->willReturn(true);

        $stub->method('contains')
             ->willReturn(true);

        $stub->method('remove')
             ->willReturn(true);

        $stub->method('removeById')
            ->willReturn(true);

        return $stub;
    }

    /**
     * @return StorageInterface
     */
    private function getBadStorageStub()
    {
        /** @var object $stub */
        $stub = $this->getMockBuilder('Transfer\Storage\StorageInterface')
            ->getMock();

        $stub->method('add')
             ->willReturn(false);

        $stub->method('findById')
             ->will($this->throwException(new ObjectNotFoundException(null)));

        $stub->method('containsId')
             ->willReturn(false);

        $stub->method('contains')
            ->willReturn(false);

        $stub->method('remove')
             ->willReturn(false);

        $stub->method('removeById')
            ->willReturn(false);

        $stub->method('all')
             ->willReturn(null);

        return $stub;
    }
}
