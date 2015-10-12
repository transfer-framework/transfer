<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Manifest;

/**
 * Manifest chain used in console commands.
 */
class ManifestChain
{
    /**
     * @var array Manifest collection
     */
    protected $manifests = array();

    /**
     * Adds manifest to chain.
     *
     * @param ManifestInterface $manifest
     *
     * @return $this
     */
    public function addManifest(ManifestInterface $manifest)
    {
        $this->manifests[$manifest->getName()] = $manifest;

        return $this;
    }

    /**
     * Returns registered manifests.
     *
     * @return array Manifest collection
     */
    public function getManifests()
    {
        return $this->manifests;
    }

    /**
     * Finds a manifest by name.
     *
     * @param string $name Manifest name
     *
     * @return ManifestInterface
     */
    public function getManifest($name)
    {
        if (array_key_exists($name, $this->manifests)) {
            return $this->manifests[$name];
        }
    }
}
