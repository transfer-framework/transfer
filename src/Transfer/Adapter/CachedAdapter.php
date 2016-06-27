<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter;

use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Storage\StorageInterface;

class CachedAdapter implements SourceAdapterInterface
{
    const HIT = 1;
    const MISS = 0;

    /**
     * @var StorageInterface Storage
     */
    private $storage;

    /**
     * @var SourceAdapterInterface Inner adapter
     */
    private $adapter;

    /**
     * @param StorageInterface       $storage Storage
     * @param SourceAdapterInterface $adapter Inner adapter
     */
    public function __construct(StorageInterface $storage, $adapter)
    {
        $this->storage = $storage;
        $this->adapter = $adapter;
    }

    /**
     * {@inheritdoc}
     */
    public function receive(Request $request)
    {
        $id = md5(serialize($request->getData()));

        if ($this->storage->containsId($id)) {
            /** @var Response $response */
            $response = $this->storage->findById($id);

            $response->setHeader('cache-status', self::HIT);
            $response->setHeader('cache-id', $id);
            $response->setHeader('cache-data', $response->getData());

            return $response;
        }

        $response = $this->adapter->receive($request);

        $response->setHeader('cache-status', self::MISS);
        $response->setHeader('cache-id', $id);

        $this->storage->add($response, $id);

        return $response;
    }
}
