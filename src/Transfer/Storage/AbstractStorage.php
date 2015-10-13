<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage;

use Transfer\Storage\Hashing\HashingStrategyInterface;
use Transfer\Storage\Hashing\StandardHashingStrategy;

/**
 * Abstract storage.
 */
abstract class AbstractStorage implements StorageInterface
{
    /**
     * @var HashingStrategyInterface Hashing strategy
     */
    protected $hashingStrategy;

    /**
     * @param HashingStrategyInterface|null $hashingStrategy Hashing strategy
     */
    public function __construct(HashingStrategyInterface $hashingStrategy = null)
    {
        $this->hashingStrategy = $hashingStrategy;

        if ($this->hashingStrategy === null) {
            $this->hashingStrategy = new StandardHashingStrategy();
        }
    }
}
