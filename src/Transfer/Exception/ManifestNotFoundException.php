<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Exception;

/**
 * Exception class for cases when a manifest is not found.
 */
class ManifestNotFoundException extends \Exception
{
    /**
     * @param string $name Manifest name
     */
    public function __construct($name)
    {
        parent::__construct(sprintf('Manifest "%s" not found in chain', $name));
    }
}
