<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Symfony\Component\EventDispatcher\Event;
use Transfer\Adapter\OutputAdapterInterface;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;

/**
 * Event triggered after an output adapter returns a response.
 */
class PostAdapterSendEvent extends Event
{
    /**
     * @var OutputAdapterInterface Output adapter
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
     * @param OutputAdapterInterface $adapter     Output adapter
     * @param Request                $request     Request sent to adapter
     * @param Response               $response    Response returned by adapter
     * @param float                  $elapsedTime Elapsed time
     */
    public function __construct(OutputAdapterInterface $adapter, Request $request, Response $response, $elapsedTime = 0.0)
    {
        $this->adapter = $adapter;
        $this->request = $request;
        $this->response = $response;
        $this->elapsedTime = $elapsedTime;
    }

    /**
     * Returns output adapter.
     *
     * @return OutputAdapterInterface Output adapter
     */
    public function getOutputAdapter()
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
