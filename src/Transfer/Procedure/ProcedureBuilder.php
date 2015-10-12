<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Procedure;

use Transfer\Adapter\CallbackAdapter;
use Transfer\Adapter\InputAdapterInterface;
use Transfer\Adapter\OutputAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Worker\CallbackWorker;
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
            'inputs' => array(),
            'workers' => array(),
            'outputs' => array(),
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
     * Adds an input instruction to a procedure.
     *
     * @param InputAdapterInterface|callable $adapter Input adapter
     * @param Request                        $request Request sent to input adapter
     *
     * @return $this
     */
    public function addInput($adapter, Request $request = null)
    {
        if (is_callable($adapter)) {
            $adapter = new CallbackAdapter($adapter, null);
        }

        if ($request == null) {
            $request = new Request();
        }

        $this->definitions[$this->context]['inputs'][] = array($adapter, $request);

        return $this;
    }

    /**
     * Adds an output instruction to a procedure.
     *
     * @param OutputAdapterInterface|callable $adapter Output adapter
     *
     * @return $this
     */
    public function addOutput($adapter)
    {
        if (is_callable($adapter)) {
            $adapter = new CallbackAdapter(null, $adapter);
        }

        $this->definitions[$this->context]['outputs'][] = $adapter;

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
        if (is_callable($worker)) {
            $worker = new CallbackWorker($worker);
        }

        $this->definitions[$this->context]['workers'][] = $worker;

        return $this;
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
}
