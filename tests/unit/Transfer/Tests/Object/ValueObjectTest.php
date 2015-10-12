<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Object;

use Transfer\Data\ValueObject;

class ValueObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ValueObject
     */
    private $object;

    /**
     * Setup.
     */
    protected function setUp()
    {
        $this->object = new ValueObject(null);
    }

    /**
     * Tests equality of data.
     */
    public function testData()
    {
        $this->assertEquals(null, $this->object->data);
        $this->assertEquals(null, $this->object->getData());
    }

    /**
     * Tests property functionality.
     */
    public function testProperty()
    {
        $this->object->setProperty('key_1', 'value_1');
        $this->object->setProperty('key_2', 'value_2');

        $this->assertEquals('value_1', $this->object->getProperty('key_1'));

        $this->assertCount(2, $this->object->getProperties());
    }
}
