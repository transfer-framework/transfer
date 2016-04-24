<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Data;

use Transfer\Data\TreeObject;
use Transfer\Data\ValueObject;

class TreeObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests addNode and removeNode functionality.
     *
     * @dataProvider providePairNodes
     *
     * @param $nodesToAdd
     * @param $nodeToRemove
     * @param $expectedSet
     */
    public function testRemoveNode($nodesToAdd, $nodeToRemove, $expectedSet)
    {
        $object = new TreeObject(null);
        foreach ($nodesToAdd as $node) {
            $object->addNode($node);
        }

        $this->assertSameSize($nodesToAdd, $object->getNodes());

        foreach ($nodeToRemove as $node) {
            $object->removeNode($node);
        }

        $object->removeNode(new ValueObject('not-in-existing-set'));

        $this->assertSameSize($expectedSet, $object->getNodes());
    }

    /**
     * @return array
     */
    public function providePairNodes()
    {
        $nodes = array(
            new ValueObject(null),
            new ValueObject(null),
            new ValueObject(null),
        );

        return array(
            array(
                $nodes,
                array(
                    $nodes[0],
                ),
                array(
                    $nodes[1],
                    $nodes[2],
                ),
            ),
        );
    }
}
