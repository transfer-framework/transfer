<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Storage\Hashing;

use Transfer\Storage\Hashing\StandardHashingStrategy;

class StandardHashingStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests hashing.
     */
    public function testHash()
    {
        $strategy = new StandardHashingStrategy();

        $this->assertEquals('098f6bcd4621d373cade4e832627b4f6', $strategy->hash('test'));
        $this->assertEquals('acc75d777f16492f95ba7c572335b7f7', $strategy->hash(array('test')));
    }

    /**
     * Tests hashing on unsupported type.
     */
    public function testHashOnUnsupportedType()
    {
        $strategy = new StandardHashingStrategy();

        $this->setExpectedException('\InvalidArgumentException');

        $strategy->hash(null);
    }
}
