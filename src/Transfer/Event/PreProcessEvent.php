<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Symfony\Component\EventDispatcher\Event;
use Transfer\Processor\ProcessorInterface;

/**
 * Event triggered before a process is started.
 */
class PreProcessEvent extends Event
{
    /**
     * @var ProcessorInterface Processor that starts the process
     */
    protected $processor;

    /**
     * @param ProcessorInterface $processor Processor that starts the process
     */
    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Returns the processor that is going to start the process.
     *
     * @return ProcessorInterface Processor that starts the process
     */
    public function getProcessor()
    {
        return $this->processor;
    }
}
