<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Manifest;

use Transfer\Processor\SequentialProcessor;

/**
 * Abstract manifest.
 */
abstract class AbstractManifest implements ManifestInterface
{
    /**
     * @var SequentialProcessor Pre-initialized processor
     */
    private $processor;

    public function __construct()
    {
        $this->processor = new SequentialProcessor();
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessor()
    {
        return $this->processor;
    }
}
