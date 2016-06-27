<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage;

use Transfer\Storage\Exception\ObjectNotFoundException;
use Transfer\Storage\Hashing\HashingStrategyInterface;

/**
 * File storage.
 */
class FileStorage extends AbstractStorage
{
    /**
     * @var string Storage directory
     */
    private $directory;

    /**
     * @param string                   $directory       Storage directory
     * @param HashingStrategyInterface $hashingStrategy Hashing strategy
     */
    public function __construct($directory = '/tmp', HashingStrategyInterface $hashingStrategy = null)
    {
        parent::__construct($hashingStrategy);

        $this->directory = $directory;
    }

    /**
     * {@inheritdoc}
     */
    public function add($object, $id = null)
    {
        if ($id === null) {
            $id = $this->hashingStrategy->hash($object);
        }

        file_put_contents(sprintf('%s/%s', $this->directory, $id), serialize($object));

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
        return file_exists(sprintf('%s/%s', $this->directory, $id));
    }

    /**
     * {@inheritdoc}
     */
    public function findById($id)
    {
        if (!$this->containsId($id)) {
            throw new ObjectNotFoundException($id);
        }

        return unserialize(file_get_contents(sprintf('%s/%s', $this->directory, $id)));
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
        unlink(sprintf('%s/%s', $this->directory, $id));

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        throw new \Exception('Unsupported method.');
    }
}
