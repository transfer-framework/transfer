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
 * Sequential processor.
 */
class SequentialProcessor extends EventDrivenProcessor
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Sequential processor';
    }

    /**
     * {@inheritdoc}
     */
    protected function handleProcedure(Procedure $procedure)
    {
        foreach ((array) $procedure->getSources() as $source) {
            list($adapter, $request) = $source;

            $response = $this->handleSource($adapter, $request);

            while ($object = $this->nextObject($response->getIterator())) {
                $storage = $this->prepareLocalStorage($object);

                $this->handleWorkers($procedure->getWorkers(), $storage);

                $this->handleTargets($procedure->getTargets(), new Request($storage->all()));

                $this->mergeStorage($storage);
            }
        }
    }
}
