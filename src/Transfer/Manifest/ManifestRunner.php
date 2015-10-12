<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Manifest;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Transfer\Procedure\ProcedureBuilder;

/**
 * Manifest runner.
 */
class ManifestRunner
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Propagates the event dispatcher onto processor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Configures and runs a manifest.
     *
     * @param ManifestInterface $manifest Manifest to be run
     */
    public function run(ManifestInterface $manifest)
    {
        $builder = new ProcedureBuilder();
        $processor = $manifest->getProcessor();

        if ($this->dispatcher) {
            $processor->setEventDispatcher($this->dispatcher);
        }

        $manifest->configureProcedureBuilder($builder);
        $manifest->configureProcessor($manifest->getProcessor());

        $procedure = $builder->getProcedure();

        $processor
            ->addProcedure($procedure)
            ->process();
    }
}
