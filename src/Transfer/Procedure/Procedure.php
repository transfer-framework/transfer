<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Procedure;

/**
 * Procedure model.
 */
class Procedure
{
    /**
     * @var Procedure Parent procedure
     */
    protected $parent = null;

    /**
     * @var array Children
     */
    protected $children = array();

    /**
     * @var string Procedure name
     */
    protected $name;

    /**
     * @var array Source collection
     */
    protected $sources = array();

    /**
     * @var array Worker collection
     */
    protected $workers = array();

    /**
     * @var array Target collection
     */
    protected $targets = array();

    /**
     * @param string    $name    Procedure name
     * @param array     $sources Source collection
     * @param array     $workers Worker collection
     * @param array     $targets Target collection
     * @param Procedure $parent  Parent procedure
     */
    public function __construct($name = null, $sources = array(), $workers = array(), $targets = array(), Procedure $parent = null)
    {
        $this->name = $name;
        $this->sources = $sources;
        $this->workers = $workers;
        $this->targets = $targets;
        $this->parent = $parent;
    }

    /**
     * Creates a procedure from a definition.
     *
     * Procedure definition must be an array consisting of following elements:
     *   * name : string
     *   * sources : array of source adapters
     *   * workers : array of workers
     *   * targets : array of target adapters
     *
     * @param array     $definition Procedure definition (how a procedure should be built)
     * @param Procedure $parent     Parent procedure
     *
     * @return Procedure
     */
    public static function createFromDefinition(array $definition, Procedure $parent = null)
    {
        $procedure = new self(
            $definition['name'],
            $definition['sources'],
            $definition['workers'],
            $definition['targets'],
            $parent
        );

        foreach ($definition['children'] as $child) {
            $procedure->addChild(self::createFromDefinition($child, $procedure));
        }

        return $procedure;
    }

    /**
     * Returns parent procedure.
     *
     * @return Procedure Parent procedure
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Returns procedure name.
     *
     * @return string Procedure name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns children.
     *
     * @return array Children procedures
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Tests, if the procedure has children (sub-procedures).
     *
     * @return bool True, if procedure has children
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * Adds child procedure.
     *
     * @param Procedure $child Child procedure
     */
    public function addChild(Procedure $child)
    {
        $this->children[] = $child;
    }

    /**
     * Tests, if a (sub-)procedure exists.
     *
     * @param string|array $name    Procedure name
     * @param Procedure    $context Current context
     * @param int          $level   Current level
     *
     * @return bool True, if procedure exists
     */
    public function procedureExists($name, $context = null, $level = 0)
    {
        $context = $this->normalizeContext($context);

        if (!is_array($name)) {
            $name = array($name);
        }

        /** @var Procedure $procedure */
        foreach ($context->getChildren() as $procedure) {
            if ($procedure->getName() == $name[$level]) {
                return true;
            }

            return $this->procedureExists($name, $procedure, $level++);
        }

        return false;
    }

    /**
     * Returns source collection.
     *
     * @return array Source collection
     */
    public function getSources()
    {
        return array_merge($this->sources, $this->getParentSettings('input'));
    }

    /**
     * Returns worker collection.
     *
     * @return array Worker collection
     */
    public function getWorkers()
    {
        return array_merge($this->workers, $this->getParentSettings('worker'));
    }

    /**
     * Returns target collection.
     *
     * @return array Target collection
     */
    public function getTargets()
    {
        return array_merge($this->targets, $this->getParentSettings('target'));
    }

    /**
     * Returns parent settings.
     *
     * @param string    $type    Setting type (source, worker or target)
     * @param Procedure $context
     *
     * @return array Setting collection
     *
     * @internal
     */
    private function getParentSettings($type, $context = null)
    {
        $context = $this->normalizeContext($context);

        $methods = array(
            'source' => 'getSources',
            'worker' => 'getWorkers',
            'target' => 'getTargets',
        );

        if (!array_key_exists($type, $methods) || $context->getParent() === null) {
            return array();
        }

        $settings = array_merge(
            $this->getParentComponentSettings($context->getParent(), $methods[$type]),
            $this->getParentSettings($type, $context->getParent())
        );

        return $settings;
    }

    /**
     * @param Procedure $context
     * @param string    $method
     *
     * @return array
     *
     * @internal
     */
    private function getParentComponentSettings($context, $method)
    {
        $settings = array();

        foreach (call_user_func(array($context, $method)) as $setting) {
            $settings[] = $setting;
        }

        return $settings;
    }

    /**
     * @param Procedure|null $context
     *
     * @return Procedure
     *
     * @internal
     */
    private function normalizeContext($context = null)
    {
        if ($context === null) {
            $context = $this;
        }

        return $context;
    }
}
