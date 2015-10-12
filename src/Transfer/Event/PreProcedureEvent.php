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

/**
 * Event triggered before a procedure is started.
 */
class PreProcedureEvent extends Event
{
    /**
     * @var Procedure Procedure to be started
     */
    protected $procedure;

    /**
     * @param $procedure Procedure to be started
     */
    public function __construct($procedure)
    {
        $this->procedure = $procedure;
    }

    /**
     * Returns procedure which is going to be started.
     *
     * @return Procedure Procedure to be started
     */
    public function getProcedure()
    {
        return $this->procedure;
    }
}
