<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter;

use Psr\Cache\CacheItemPoolInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;

class CacheAdapter implements SourceAdapterInterface
{
    const HIT = 1;
    const MISS = 0;

    /**
     * @var CacheItemPoolInterface Cache pool
     */
    private $cachePool;

    /**
     * @var SourceAdapterInterface Inner adapter
     */
    private $adapter;

    /**
     * @var callable|null Key generator
     */
    private $keyGeneratorCallback;

    /**
     * @param CacheItemPoolInterface $cachePool            Cache pool
     * @param SourceAdapterInterface $adapter              Inner adapter
     * @param callable               $keyGeneratorCallback Key generator
     */
    public function __construct(CacheItemPoolInterface $cachePool, $adapter, $keyGeneratorCallback = null)
    {
        $this->cachePool = $cachePool;
        $this->adapter = $adapter;
        $this->keyGeneratorCallback = $keyGeneratorCallback;
    }

    /**
     * {@inheritdoc}
     */
    public function receive(Request $request)
    {
        if (is_callable($this->keyGeneratorCallback)) {
            $key = call_user_func_array($this->keyGeneratorCallback, array($request));
        } else {
            $key = md5(serialize($request->getData()));
        }

        $item = $this->cachePool->getItem($key);

        if (!$item->isHit()) {
            $response = $this->adapter->receive($request);

            $response->setHeader('cache-status', self::MISS);
            $response->setHeader('cache-key', $key);

            $item->set($response);

            $this->cachePool->save($item);

            return $response;
        }

        /** @var Response $response */
        $response = $item->get();

        $response->setHeader('cache-status', self::HIT);
        $response->setHeader('cache-key', $key);
        $response->setHeader('cache-data', $response->getData());

        return $response;
    }
}
