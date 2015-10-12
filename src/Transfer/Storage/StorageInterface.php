<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage;

/**
 * Common storage interface.
 */
interface StorageInterface
{
    /**
     * Adds an object to storage.
     *
     * @param mixed  $object Object
     * @param string $id     ID
     *
     * @return bool Returns true, if value had been set, otherwise false
     */
    public function add($object, $id = null);

    /**
     * Tests whether an object is in storage.
     *
     * @param mixed $object Object
     *
     * @return bool True, if the object is in storage
     */
    public function contains($object);

    /**
     * Tests whether an object associated with the supplied ID is in storage.
     *
     * @param string $id ID
     *
     * @return bool True, if the ID is in storage
     */
    public function containsId($id);

    /**
     * Finds an object associated with the supplied ID.
     *
     * @param string $id ID
     *
     * @return mixed Object
     *
     * @throws Exception\ObjectNotFoundException
     */
    public function findById($id);

    /**
     * Removes the object from storage.
     *
     * @param mixed $object Object
     *
     * @return bool True, if the object had been removed
     */
    public function remove($object);

    /**
     * Removes the object associated with the supplied ID from storage.
     *
     * @param string $id ID
     *
     * @return bool True, if the object had been removed
     */
    public function removeById($id);

    /**
     * Returns iterable ID-Object pair object.
     *
     * @return array|\Traversable|\Iterator ID-Object pairs
     */
    public function all();
}
