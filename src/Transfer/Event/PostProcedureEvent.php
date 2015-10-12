<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Transfer\Procedure\Procedure;
use Symfony\Component\EventDispatcher\Event;
use Transfer\Processor\ProcessorInterface;

/**
 * Event triggered after a procedure is finished.
 */
class PostProcedureEvent extends Event
{
    /**
     * @var Procedure Finished procedure
     */
    protected $procedure;

    /**
     * @var ProcessorInterface Processor that finished the procedure
     */
    private $processor;

    /**
     * @param Procedure          $procedure Finished procedure
     * @param ProcessorInterface $processor Processor that finished the procedure
     */
    public function __construct(Procedure $procedure, ProcessorInterface $processor)
    {
        $this->procedure = $procedure;
        $this->processor = $processor;
    }

    /**
     * Returns finished procedure.
     *
     * @return Procedure Finished procedure
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Returns processor that finished the procedure.
     *
     * @return ProcessorInterface Processor that finished the procedure
     */
    public function getProcessor()
    {
        return $this->processor;
    }
}
