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
 * Event triggered after a process is finished.
 */
class PostProcessEvent extends Event
{
    /**
     * @var ProcessorInterface Processor that finished the process
     */
    protected $processor;

    /**
     * @param ProcessorInterface $processor Processor that finished the process
     */
    public function __construct(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Returns processor that finished the process.
     *
     * @return ProcessorInterface Processor that finished the process
     */
    public function getProcessor()
    {
        return $this->processor;
    }
}
