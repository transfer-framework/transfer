<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Processor;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Transfer\Adapter\InputAdapterInterface;
use Transfer\Adapter\OutputAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Exception\MissingResponseException;
use Transfer\Procedure\Procedure;
use Transfer\Storage\InMemoryStorage;
use Transfer\Storage\StorageInterface;
use Transfer\Storage\StorageStack;
use Transfer\Storage\StorageStackAwareInterface;
use Transfer\Worker\WorkerInterface;

/**
 * Partial processor implementation.
 */
abstract class Processor implements ProcessorInterface, LoggerAwareInterface, StorageStackAwareInterface
{
    /**
     * @var array Procedures
     */
    protected $procedures;

    /**
     * @var StorageStack Storage stack
     */
    protected $stack;

    /**
     * @var LoggerInterface Logger
     */
    protected $logger;

    public function __construct()
    {
        $this->stack = new StorageStack(array(
            'global' => new InMemoryStorage(),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function addProcedure(Procedure $procedure)
    {
        $this->procedures[] = $procedure;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setStorageStack(StorageStack $stack)
    {
        $this->stack = $stack;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function process()
    {
        $this->handleProcedures($this->procedures);
    }

    /**
     * Handles procedure.
     *
     * @param Procedure $procedure Procedure to handle
     */
    abstract protected function handleProcedure(Procedure $procedure);

    /**
     * Calls procedure handle.
     *
     * @param Procedure $procedure Procedure to handle
     */
    protected function handleProcedureOuter(Procedure $procedure)
    {
        $this->handleProcedure($procedure);
    }

    /**
     * Handles procedures.
     *
     * @param array $procedures Procedures to handle
     */
    protected function handleProcedures(array $procedures)
    {
        /** @var Procedure $procedure */
        foreach ($procedures as $procedure) {
            $this->handleProcedureOuter($procedure);

            if ($procedure->hasChildren()) {
                $this->handleProcedures($procedure->getChildren());
            }
        }
    }

    /**
     * Handles input.
     *
     * @param InputAdapterInterface $adapter Input adapter
     * @param Request               $request Request to handle
     *
     * @return Response Input adapter response
     *
     * @throws MissingResponseException
     */
    protected function handleInput(InputAdapterInterface $adapter, Request $request)
    {
        $this->injectDependencies($adapter);

        $response = $adapter->receive($request);

        if ($response == null) {
            throw new MissingResponseException($adapter);
        }

        return $response;
    }

    /**
     * Handles inputs.
     *
     * @param array $inputs Inputs
     *
     * @return \Iterator Iterator for all input response objects
     */
    protected function handleInputs(array $inputs)
    {
        $responses = array();

        foreach ($inputs as $input) {
            list($adapter, $request) = $input;
            $responses[] = $this->handleInput($adapter, $request);
        }

        $iterator = new \AppendIterator();

        /** @var Response $response */
        foreach ($responses as $response) {
            $iterator->append($response->getData());
        }

        return $iterator;
    }

    /**
     * Handles worker.
     *
     * @param WorkerInterface  $worker  Worker
     * @param mixed            $object  Object to handle
     * @param StorageInterface $storage Associated storage
     */
    protected function handleWorker(WorkerInterface $worker, $object, StorageInterface $storage)
    {
        $this->injectDependencies($worker);

        $modifiedObject = $worker->handle($object);

        if ($modifiedObject == null) {
            $storage->remove($object);
        } elseif ($modifiedObject !== $object) {
            $storage->remove($object);
            $storage->add($modifiedObject);
        }
    }

    /**
     * Handles workers.
     *
     * @param array            $workers Workers
     * @param mixed            $object  Object to handle
     * @param StorageInterface $storage Associated storage
     */
    protected function handleWorkers(array $workers, $object, StorageInterface $storage)
    {
        foreach ($workers as $worker) {
            $this->handleWorker($worker, $object, $storage);
        }
    }

    /**
     * Handles output.
     *
     * @param OutputAdapterInterface $adapter Output adapter
     * @param Request                $request Request to handle
     *
     * @return Response Output adapter response
     *
     * @throws MissingResponseException
     */
    protected function handleOutput(OutputAdapterInterface $adapter, Request $request)
    {
        $this->injectDependencies($adapter);

        $response = $adapter->send($request);

        if ($response == null) {
            throw new MissingResponseException($adapter);
        }

        return $response;
    }

    /**
     * Handles outputs.
     *
     * @param array   $outputs Outputs
     * @param Request $request Request to handle
     *
     * @return array Output responses
     */
    protected function handleOutputs(array $outputs, Request $request)
    {
        $responses = array();

        foreach ($outputs as $output) {
            $responses[] = $this->handleOutput($output, $request);
        }

        return $responses;
    }

    /**
     * Returns next object for an iterator.
     *
     * @param \Iterator $iterator Iterator to return the next object for
     *
     * @return mixed|false Object or false, if no object can be returned
     */
    protected function nextObject(\Iterator $iterator)
    {
        if ($iterator->valid()) {
            $object = $iterator->current();

            $iterator->next();

            return $object;
        }

        return false;
    }

    /**
     * Creates scoped storage.
     *
     * @param string $scope Scope name
     *
     * @return InMemoryStorage Local storage
     */
    protected function createStorage($scope)
    {
        $storage = new InMemoryStorage();

        $this->stack->setScope($scope, $storage);

        return $storage;
    }

    /**
     * Injects dependencies.
     *
     * @param object $component Component
     */
    protected function injectDependencies($component)
    {
        if ($component instanceof StorageStackAwareInterface) {
            $component->setStorageStack($this->stack);
        }

        if ($component instanceof LoggerAwareInterface && $this->logger instanceof LoggerInterface) {
            $component->setLogger($this->logger);
        }
    }
}
