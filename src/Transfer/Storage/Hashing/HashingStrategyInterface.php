<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage\Hashing;

/**
 * Common hashing strategy interface.
 */
interface HashingStrategyInterface
{
    /**
     * Generates a hash based on an object.
     *
     * @param $object Object to generate hash for
     *
     * @return string Hash
     */
    public function hash($object);
}
