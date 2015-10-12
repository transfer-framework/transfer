<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Data;

/**
 * Generic object interface implementation.
 */
class ValueObject implements ObjectInterface
{
    /**
     * @var mixed Associated data
     */
    public $data;

    /**
     * @var array Property collection
     */
    protected $properties = array();

    /**
     * @param mixed $data       Associated data
     * @param array $properties Properties
     */
    public function __construct($data, array $properties = array())
    {
        $this->data = $data;
        $this->properties = $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty($key)
    {
        return isset($this->properties[$key]) ? $this->properties[$key] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function setProperty($key, $value)
    {
        $this->properties[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }
}
