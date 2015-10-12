<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage\Exception;

use Transfer\Storage\StorageInterface;

/**
 * Exception class for cases when storage is not iterable.
 */
class BadStorageIterableException extends \Exception
{
    /**
     * @param StorageInterface $storage Storage
     */
    public function __construct(StorageInterface $storage)
    {
        parent::__construct(
            sprintf('Storage "%" is not iterable, got "%s"', get_class($storage), gettype($storage->all()))
        );
    }
}
