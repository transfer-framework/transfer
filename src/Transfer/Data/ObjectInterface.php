<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Data;

/**
 * Common object interface.
 */
interface ObjectInterface
{
    /**
     * Returns properties.
     *
     * @return array Property collection
     */
    public function getProperties();

    /**
     * Finds a property by key.
     *
     * @param string $key Key
     *
     * @return mixed Value
     */
    public function getProperty($key);

    /**
     * Sets a property.
     *
     * @param string $key   Property key
     * @param mixed  $value Property value
     */
    public function setProperty($key, $value);

    /**
     * Returns associated data.
     *
     * @return mixed
     */
    public function getData();
}
