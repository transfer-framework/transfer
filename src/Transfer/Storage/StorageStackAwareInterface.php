<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage;

/**
 * Interface for making objects storage stack aware.
 */
interface StorageStackAwareInterface
{
    /**
     * @param StorageStack $stack Storage stack
     */
    public function setStorageStack(StorageStack $stack);
}
