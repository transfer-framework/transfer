<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Data;

/**
 * Value object with tree node functionality.
 */
class TreeObject extends ValueObject
{
    /**
     * @var array Node collection
     */
    protected $nodes = array();

    /**
     * Returns nodes.
     *
     * @return array Node collection
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Adds node.
     *
     * @param ObjectInterface $node Node to add
     */
    public function addNode($node)
    {
        $this->nodes[spl_object_hash($node)] = $node;
    }

    /**
     * Removes node.
     *
     * @param ObjectInterface $node Node to remove
     */
    public function removeNode(ObjectInterface $node)
    {
        unset($this->nodes[spl_object_hash($node)]);
    }
}
