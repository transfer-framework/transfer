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
        $response = $this->handleSources($procedure->getSources());

        while ($object = $this->nextObject($response)) {
            $storage = $this->prepareLocalStorage($object);

            $this->handleWorkers($procedure->getWorkers(), $storage);

            $this->mergeStorage($storage);
        }

        $this->handleTargets($procedure->getTargets(), new Request($this->stack->getScope('global')->all()));
    }
}
