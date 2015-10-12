<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Processor;

use Transfer\Adapter\Transaction\Request;
use Transfer\Procedure\Procedure;

/**
 * Non-sequential processor.
 */
class NonSequentialProcessor extends EventDrivenProcessor
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Non-sequential processor';
    }

    /**
     * {@inheritdoc}
     */
    protected function handleProcedure(Procedure $procedure)
    {
        $response = $this->handleInputs($procedure->getInputs());

        while ($object = $this->nextObject($response)) {
            $this->stack->getScope('global')->add($object);

            $this->handleWorkers($procedure->getWorkers(), $object, $this->stack->getScope('global'));
        }

        $this->handleOutputs($procedure->getOutputs(), new Request($this->stack->getScope('global')->all()));
    }
}
