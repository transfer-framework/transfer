<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Symfony\Component\EventDispatcher\Event;
use Transfer\Adapter\InputAdapterInterface;
use Transfer\Adapter\Transaction\Response;

/**
 * Event triggered after an input adapter returns a response.
 */
class PostAdapterReceiveEvent extends Event
{
    /**
     * @var InputAdapterInterface Input adapter
     */
    protected $adapter;

    /**
     * @var Response Response returned by adapter
     */
    protected $response;

    /**
     * @var float Elapsed time
     */
    protected $elapsedTime;

    /**
     * @param InputAdapterInterface $adapter     Input adapter
     * @param Response              $response    Response returned by adapter
     * @param float                 $elapsedTime Elapsed time
     */
    public function __construct(InputAdapterInterface $adapter, Response $response, $elapsedTime = 0.0)
    {
        $this->adapter = $adapter;
        $this->response = $response;
        $this->elapsedTime = $elapsedTime;
    }

    /**
     * Returns input adapter.
     *
     * @return InputAdapterInterface Input adapter
     */
    public function getInputAdapter()
    {
        return $this->adapter;
    }

    /**
     * Returns response returned by adapter.
     *
     * @return Response Response returned by adapter
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns elapsed time.
     *
     * @return float Elapsed time
     */
    public function getElapsedTime()
    {
        return $this->elapsedTime;
    }
}
