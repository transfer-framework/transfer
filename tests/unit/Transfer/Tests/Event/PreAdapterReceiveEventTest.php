<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Event;

use Transfer\Adapter\SourceAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Event\PreAdapterReceiveEvent;

class PreAdapterReceiveEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests construct.
     */
    public function testConstruct()
    {
        /** @var SourceAdapterInterface $adapter */
        $adapter = $this->getMockBuilder('Transfer\Adapter\SourceAdapterInterface')->getMock();

        /** @var Request $request */
        $request = $this->getMockBuilder('Transfer\Adapter\Transaction\Request')->getMock();

        $event = new PreAdapterReceiveEvent($adapter, $request);

        $this->assertSame($adapter, $event->getSource());
        $this->assertSame($request, $event->getRequest());
    }
}
