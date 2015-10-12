<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\InputAdapterInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event triggered before an input adapter receives a request.
 */
class PreAdapterReceiveEvent extends Event
{
    /**
     * @var InputAdapterInterface Input adapter
     */
    protected $input;

    /**
     * @var Request Request sent to adapter
     */
    protected $request;

    /**
     * @param InputAdapterInterface $input   Input adapter
     * @param Request               $request Request sent to adapter
     */
    public function __construct(InputAdapterInterface $input, Request $request = null)
    {
        $this->input = $input;
        $this->request = $request;
    }

    /**
     * Returns input adapter.
     *
     * @return InputAdapterInterface Input adapter
     */
    public function getInput()
    {
        return $this->input;
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
