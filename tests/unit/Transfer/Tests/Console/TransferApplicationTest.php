<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Console;

use Transfer\Console\TransferApplication;

class TransferApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests application initialization.
     */
    public function testConstruct()
    {
        $application = new TransferApplication();

        $this->assertEquals('v0.3', $application->getVersion());
        $this->assertCount(5, $application->all());
    }
}
