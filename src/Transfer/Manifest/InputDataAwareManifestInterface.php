<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Manifest;

/**
 * Interface for making manifests input aware.
 */
interface InputDataAwareManifestInterface
{
    /**
     * Sets input data.
     *
     * @param mixed $data Input data
     */
    public function setInputData($data);
}
