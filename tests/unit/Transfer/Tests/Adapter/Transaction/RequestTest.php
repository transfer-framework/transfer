<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter\Transaction;

use Transfer\Adapter\Transaction\Request;
use Transfer\Data\ValueObject;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstruct()
    {
        $request = new Request();
        $this->assertCount(0, $request->getHeaders());

        $request = new Request(
            array(
                new ValueObject(null),
            ),
            array(
            'key' => 'value',
            )
        );

        $this->assertCount(1, $request->getHeaders());
        $this->assertCount(1, $request->getData());
    }

    /**
     * Tests createWithHeaders method.
     */
    public function testCreateWithHeaders()
    {
        $request = Request::createWithHeaders(array('header'));

        $this->assertContains('header', $request->getHeaders());
    }
}
