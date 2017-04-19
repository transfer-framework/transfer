<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage;

use Transfer\Storage\Exception\ObjectNotFoundException;

/**
 * In-memory storage.
 */
class InMemoryStorage extends AbstractStorage
{
    /**
     * @var array
     */
    private $data = array();

    /**
     * {@inheritdoc}
     */
    public function add($object, $id = null)
    {
        if ($id === null) {
            $id = $this->hashingStrategy->hash($object);
        }

        $this->data[$id] = $object;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function set($id, $object)
    {
        $this->data[$id] = $object;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($object)
    {
        return $this->containsId($this->hashingStrategy->hash($object));
    }

    /**
     * {@inheritdoc}
     */
    public function containsId($id)
    {
        return array_key_exists($id, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function findById($id)
    {
        if (!$this->containsId($id)) {
            throw new ObjectNotFoundException($id);
        }

        return $this->data[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {
        return $this->removeById($this->hashingStrategy->hash($object));
    }

    /**
     * {@inheritdoc}
     */
    public function removeById($id)
    {
        unset($this->data[$id]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->data;
    }
}
