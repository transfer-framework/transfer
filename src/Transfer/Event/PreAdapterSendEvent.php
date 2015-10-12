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

/**
 * Event triggered before an output adapter receives a request.
 */
class PreAdapterSendEvent extends Event
{
    /**
     * @var OutputAdapterInterface Output adapter
     */
    protected $output;

    /**
     * @var Request Request sent to adapter
     */
    protected $request;

    /**
     * @param OutputAdapterInterface $output  Output adapter
     * @param Request                $request Request sent to adapter
     */
    public function __construct(OutputAdapterInterface $output, Request $request)
    {
        $this->output = $output;
        $this->request = $request;
    }

    /**
     * Returns output adapter.
     *
     * @return OutputAdapterInterface Output adapter
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Returns request which is going to be sent to adapter.
     *
     * @return Request Request sent to adapter
     */
    public function getRequest()
    {
        return $this->request;
    }
}
