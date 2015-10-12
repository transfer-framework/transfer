<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage\Hashing;

/**
 * Standard hashing strategy.
 */
class StandardHashingStrategy implements HashingStrategyInterface
{
    /**
     * {@inheritdoc}
     */
    public function hash($object)
    {
        if (is_object($object)) {
            return spl_object_hash($object);
        } elseif (is_string($object)) {
            return md5($object);
        } elseif (is_array($object)) {
            return md5(serialize($object));
        }

        throw new \InvalidArgumentException(sprintf('Object of type "%s" can not be hashed.', gettype($object)));
    }
}
