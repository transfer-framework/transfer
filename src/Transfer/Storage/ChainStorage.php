<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage;

use Transfer\Storage\Exception\BadStorageIterableException;
use Transfer\Storage\Exception\ObjectNotFoundException;

/**
 * Storage chaining utility.
 */
class ChainStorage implements StorageInterface
{
    /**
     * @var array Storage collection
     */
    private $storageCollection;

    /**
     * @param array $storageCollection Storage collection
     */
    public function __construct(array $storageCollection)
    {
        $this->storageCollection = $storageCollection;

        foreach ($this->storageCollection as $storage) {
            if (!$storage instanceof StorageInterface) {
                throw new \InvalidArgumentException('Storage collection must contain only StorageInterface instances.');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add($object, $id = null)
    {
        /** @var StorageInterface $storage */
        foreach ($this->storageCollection as $storage) {
            if ($storage->add($object, $id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function set($id, $object)
    {
        /** @var StorageInterface $storage */
        foreach ($this->storageCollection as $storage) {
            if ($storage->set($id, $object)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function contains($object)
    {
        /** @var StorageInterface $storage */
        foreach ($this->storageCollection as $storage) {
            if ($storage->contains($object)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function containsId($id)
    {
        /** @var StorageInterface $storage */
        foreach ($this->storageCollection as $storage) {
            if ($storage->containsId($id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function findById($id)
    {
        /** @var StorageInterface $storage */
        foreach ($this->storageCollection as $storage) {
            try {
                return $storage->findById($id);
            } catch (ObjectNotFoundException $e) {
                // Go to next storage instance
            }
        }

        throw new ObjectNotFoundException($id);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {
        /** @var StorageInterface $storage */
        foreach ($this->storageCollection as $storage) {
            if ($storage->remove($object)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function removeById($id)
    {
        /** @var StorageInterface $storage */
        foreach ($this->storageCollection as $storage) {
            if ($storage->removeById($id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        $iterator = new \AppendIterator();

        /** @var StorageInterface $storage */
        foreach ($this->storageCollection as $storage) {
            if (is_array($storage->all())) {
                $iterator->append(new \ArrayIterator($storage->all()));
            } elseif ($storage->all() instanceof \Iterator) {
                $iterator->append($storage->all());
            } else {
                throw new BadStorageIterableException($storage);
            }
        }

        return $iterator;
    }
}
