<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Procedure;

use Transfer\Adapter\CallbackAdapter;
use Transfer\Adapter\SourceAdapterInterface;
use Transfer\Adapter\TargetAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Worker\CallbackWorker;
use Transfer\Worker\SplitterWorker;
use Transfer\Worker\WorkerInterface;

/**
 * Procedure builder.
 */
class ProcedureBuilder
{
    /**
     * @var array Definitions
     */
    private $definitions;

    /**
     * @var string Current definition
     */
    private $context;

    public function __construct()
    {
        $this->createProcedure('root');
    }

    /**
     * Creates a procedure.
     *
     * @param string $name Procedure name
     *
     * @return $this
     */
    public function createProcedure($name)
    {
        $definition = array(
            'parent' => $this->context,
            'name' => $name,
            'sources' => array(),
            'workers' => array(),
            'targets' => array(),
            'children' => array(),
        );

        $this->definitions[$name] = $definition;

        $this->context = $name;

        return $this;
    }

    /**
     * Builds a procedure object.
     *
     * @return Procedure
     */
    public function getProcedure()
    {
        return Procedure::createFromDefinition($this->definitions['root']);
    }

    /**
     * Adds a source instruction to a procedure.
     *
     * @param SourceAdapterInterface|callable $adapter Source adapter
     * @param Request                         $request Request sent to source adapter
     *
     * @return $this
     */
    public function addSource($adapter, Request $request = null)
    {
        if ($request === null) {
            $request = new Request();
        }

        $this->addDefinition(
            'sources',
            array((is_callable($adapter) ? new CallbackAdapter($adapter, null) : $adapter), $request)
        );

        return $this;
    }

    /**
     * Adds a target instruction to a procedure.
     *
     * @param TargetAdapterInterface|callable $adapter Target adapter
     *
     * @return $this
     */
    public function addTarget($adapter)
    {
        $this->addDefinition(
            'targets',
            is_callable($adapter) ? new CallbackAdapter(null, $adapter) : $adapter
        );

        return $this;
    }

    /**
     * Adds a worker instruction to a procedure.
     *
     * @param WorkerInterface|callable $worker Worker
     *
     * @return $this
     */
    public function addWorker($worker)
    {
        $this->addDefinition(
            'workers',
            is_callable($worker) ? new CallbackWorker($worker) : $worker
        );

        return $this;
    }

    /**
     * Pushes the array elements to the local storage.
     *
     * @return $this
     */
    public function split()
    {
        return $this->addWorker(new SplitterWorker());
    }

    /**
     * Switches current procedure context to parent procedure.
     *
     * @return $this
     */
    public function end()
    {
        $definition = $this->definitions[$this->context];

        $this->context = $this->definitions[$this->context]['parent'];

        $this->definitions[$this->context]['children'][] = $definition;

        return $this;
    }

    /**
     * Adds definition.
     *
     * @param string $type
     * @param mixed  $component
     */
    private function addDefinition($type, $component)
    {
        $this->definitions[$this->context][$type][] = $component;
    }
}
