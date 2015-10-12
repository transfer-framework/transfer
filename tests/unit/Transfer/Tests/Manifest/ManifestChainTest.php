<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Manifest;

use Transfer\Manifest\ManifestChain;

class ManifestChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests manifest bag.
     */
    public function testManifestBag()
    {
        $chain = new ManifestChain();

        $manifestProphecy = $this->prophesize('Transfer\Manifest\ManifestInterface');
        $manifestProphecy->getName()->willReturn('mock');

        $manifest = $manifestProphecy->reveal();

        $chain
            ->addManifest($manifest)
            ->addManifest($manifest);

        $this->assertCount(1, $chain->getManifests());

        $this->assertEquals($manifest, $chain->getManifest('mock'));

        $this->assertNull($chain->getManifest('non-existing-manifest'));
    }
}
