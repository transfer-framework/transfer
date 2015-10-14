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
use Transfer\Storage\InMemoryStorage;
use Transfer\Storage\StorageInterface;

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

            while ($object = $this->nextObject($response->getData())) {
                $storage = $this->prepareLocalStorage($object);

                $this->handleWorkers($procedure->getWorkers(), $object, $storage);

                $this->handleTargets($procedure->getTargets(), new Request($storage->all()));

                $this->mergeStorage($storage);
            }
        }
    }

    /**
     * Prepares local storage.
     *
     * @param mixed $object Initial object
     *
     * @return InMemoryStorage Local storage
     */
    protected function prepareLocalStorage($object)
    {
        $storage = $this->createStorage('local');
        $storage->add($object);

        return $storage;
    }

    /**
     * Merges local storage with global storage.
     *
     * @param StorageInterface $storage Local storage
     */
    protected function mergeStorage(StorageInterface $storage)
    {
        foreach ($storage->all() as $id => $object) {
            $this->stack->getScope('global')->add($object);
        }
    }
}
