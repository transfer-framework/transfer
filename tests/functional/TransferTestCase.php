<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Functional;

use Transfer\Manifest\ManifestInterface;
use Transfer\Manifest\ManifestRunner;

class TransferTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @param ManifestInterface $manifest Manifest to run
     */
    public function runManifest(ManifestInterface $manifest)
    {
        $runner = new ManifestRunner();
        $runner->run($manifest);
    }
}
