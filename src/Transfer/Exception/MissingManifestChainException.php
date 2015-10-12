<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Exception;

/**
 * Exception class for cases when a manifest chain is missing.
 */
class MissingManifestChainException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Manifest chain is not set');
    }
}
