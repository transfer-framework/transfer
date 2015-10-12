<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Storage;

use Transfer\Storage\InMemoryStorage;

class InMemoryStorageTest extends StorageTestCase
{
    protected function setUp()
    {
        $this->storage = new InMemoryStorage();
    }
}
