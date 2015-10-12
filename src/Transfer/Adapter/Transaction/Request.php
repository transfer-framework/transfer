<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter\Transaction;

/**
 * Request model.
 */
class Request extends Transaction
{
    /**
     * Creates a request object with supplied headers.
     *
     * @param array $headers Header collection
     *
     * @return Request
     */
    public static function createWithHeaders($headers)
    {
        return new self(array(), $headers);
    }
}
