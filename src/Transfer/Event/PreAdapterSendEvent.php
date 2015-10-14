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

/**
 * Event triggered before a target adapter receives a request.
 */
class PreAdapterSendEvent extends Event
{
    /**
     * @var TargetAdapterInterface Target adapter
     */
    protected $target;

    /**
     * @var Request Request sent to adapter
     */
    protected $request;

    /**
     * @param TargetAdapterInterface $target  Target adapter
     * @param Request                $request Request sent to adapter
     */
    public function __construct(TargetAdapterInterface $target, Request $request)
    {
        $this->target = $target;
        $this->request = $request;
    }

    /**
     * Returns target adapter.
     *
     * @return TargetAdapterInterface Target adapter
     */
    public function getTarget()
    {
        return $this->target;
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
