<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter\Transaction;

/**
 * Common transaction model for requests and responses.
 */
abstract class Transaction extends DataAggregate
{
    /**
     * @var array Header collection
     */
    protected $headers;

    /**
     * @param array|\Iterator $data    Data collection
     * @param array           $headers Header collection
     */
    public function __construct($data = array(), $headers = array())
    {
        parent::__construct($data);

        $this->headers = $headers;
    }

    /**
     * Gets header collection.
     *
     * @return array Header collection
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Gets header by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getHeader($key)
    {
        return array_key_exists($key, $this->headers) ? $this->headers[$key] : null;
    }

    /**
     * Sets a header collection.
     *
     * @param array $headers Header collection
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }
}
