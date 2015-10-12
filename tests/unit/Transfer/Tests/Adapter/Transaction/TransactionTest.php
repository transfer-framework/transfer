<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter\Transaction;

use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Data\ValueObject;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests get- and setHeaders.
     */
    public function testGetSetHeaders()
    {
        $request = new Request();

        $request->setHeaders(array(
            'key' => 'value',
        ));

        $this->assertCount(1, $request->getHeaders());

        $this->assertEquals('value', $request->getHeader('key'));
    }

    /**
     * Tests get- and setData methods.
     */
    public function testGetSetData()
    {
        $response = new Response(array(
            new ValueObject('test'),
        ));

        $this->assertCount(1, $response->getData());

        $response->setData(array(
            new ValueObject('test'),
            new ValueObject('test'),
        ));

        $this->assertCount(2, $response->getData());
    }
}
