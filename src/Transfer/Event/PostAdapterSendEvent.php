<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Symfony\Component\EventDispatcher\Event;
use Transfer\Adapter\TargetAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;

/**
 * Event triggered after a target adapter returns a response.
 */
class PostAdapterSendEvent extends Event
{
    /**
     * @var TargetAdapterInterface Target adapter
     */
    protected $adapter;

    /**
     * @var Request Request sent to adapter
     */
    private $request;

    /**
     * @var Response Response returned by adapter
     */
    protected $response;

    /**
     * @var float Elapsed time
     */
    protected $elapsedTime;

    /**
     * @param TargetAdapterInterface $adapter     Target adapter
     * @param Request                $request     Request sent to adapter
     * @param Response               $response    Response returned by adapter
     * @param float                  $elapsedTime Elapsed time
     */
    public function __construct(TargetAdapterInterface $adapter, Request $request, Response $response, $elapsedTime = 0.0)
    {
        $this->adapter = $adapter;
        $this->request = $request;
        $this->response = $response;
        $this->elapsedTime = $elapsedTime;
    }

    /**
     * Returns target adapter.
     *
     * @return TargetAdapterInterface Target adapter
     */
    public function getTargetAdapter()
    {
        return $this->adapter;
    }

    /**
     * Returns request sent to adapter.
     *
     * @return Request Request sent to adapter
     */
    public function getRequest()
    {
        return $this->request;
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
