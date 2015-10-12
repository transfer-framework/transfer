<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage\Exception;

/**
 * Exception class for cases when an object is not found in storage.
 */
class ObjectNotFoundException extends \Exception
{
    /**
     * @param string $id ID
     */
    public function __construct($id)
    {
        parent::__construct(sprintf('Object with ID "%s" could not be found', $id));
    }
}
