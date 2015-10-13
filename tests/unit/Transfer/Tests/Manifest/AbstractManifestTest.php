<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Manifest;

class AbstractManifestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests presence of default processor.
     */
    public function testGetProcessor()
    {
        $mock = $this->getMockForAbstractClass('Transfer\Manifest\AbstractManifest');

        $this->assertInstanceOf('Transfer\Processor\ProcessorInterface', $mock->getProcessor());
    }
}
