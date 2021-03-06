<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Exception;

use Transfer\Adapter\SourceAdapterInterface;
use Transfer\Adapter\TargetAdapterInterface;

/**
 * Exception class for cases when a response is missing.
 */
class MissingResponseException extends \Exception
{
    /**
     * @param SourceAdapterInterface|TargetAdapterInterface $adapter Adapter object
     */
    public function __construct($adapter)
    {
        if (is_object($adapter)) {
            $adapter = get_class($adapter);
        }

        parent::__construct(sprintf('Adapter %s must return a Response, null given', $adapter));
    }
}
