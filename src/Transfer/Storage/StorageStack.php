<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Storage;

use Transfer\Storage\Exception\ObjectNotFoundException;

/**
 * Storage stack.
 */
class StorageStack
{
    /**
     * @var StorageInterface Scope collection
     */
    protected $scopes;

    /**
     * @param array $scopes Initial scopes
     */
    public function __construct($scopes = array())
    {
        $this->scopes = new InMemoryStorage();

        foreach ($scopes as $scope => $storage) {
            $this->scopes->add($storage, $scope);
        }
    }

    /**
     * Sets scope.
     *
     * @param string           $scope   Scope name
     * @param StorageInterface $storage Scoped storage
     */
    public function setScope($scope, StorageInterface $storage)
    {
        $this->scopes->add($storage, $scope);
    }

    /**
     * Gets scope.
     *
     * @param string $scope Scope name
     *
     * @throws ObjectNotFoundException
     *
     * @return StorageInterface Object
     */
    public function getScope($scope)
    {
        return $this->scopes->findById($scope);
    }

    /**
     * Removes scope.
     *
     * @param string $scope Scope name
     */
    public function removeScope($scope)
    {
        $this->scopes->removeById($scope);
    }

    /**
     * Returns all scopes.
     *
     * @return array|\Iterator|\Traversable Scope collection
     */
    public function getScopes()
    {
        return $this->scopes->all();
    }
}
