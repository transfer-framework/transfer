<?php

namespace Transfer\Worker;

use Transfer\Storage\StorageStack;
use Transfer\Storage\StorageStackAwareInterface;

class SplitterWorker implements WorkerInterface, StorageStackAwareInterface
{
    /**
     * @var StorageStack Storage stack
     */
    protected $stack;

    /**
     * {@inheritdoc}
     */
    public function setStorageStack(StorageStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($object)
    {
        if (!is_array($object)) {
            throw new \InvalidArgumentException(sprintf('Expected array for splitting, got %s', gettype($object)));
        }

        $storage = $this->stack->getScope('local');

        foreach ($object as $element) {
            $storage->add($element);
        }
    }
}
