<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter;

use Transfer\Adapter\LocalFileAdapter;
use Transfer\Adapter\Transaction\Request;

class LocalFileAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests receive method.
     */
    public function testAdapter()
    {
        $adapter = new LocalFileAdapter(__DIR__.'/../../../../fixtures/fixed_directory/1.txt');

        $request = new Request();

        $response = $adapter->receive($request);

        $this->assertInstanceOf('Transfer\Adapter\Transaction\Response', $response);
    }
}
