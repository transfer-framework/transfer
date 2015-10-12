<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Manifest;

use Transfer\Procedure\ProcedureBuilder;
use Transfer\Processor\ProcessorInterface;

/**
 * Common manifest interface.
 */
interface ManifestInterface
{
    /**
     * Returns manifest name.
     *
     * @return string Manifest name
     */
    public function getName();

    /**
     * Configures procedure builder.
     *
     * @param ProcedureBuilder $builder Procedure builder
     */
    public function configureProcedureBuilder(ProcedureBuilder $builder);

    /**
     * Returns a custom processor.
     *
     * @return ProcessorInterface Processor
     */
    public function getProcessor();

    /**
     * Configures processor.
     *
     * @param ProcessorInterface $processor Processor
     */
    public function configureProcessor(ProcessorInterface $processor);
}
