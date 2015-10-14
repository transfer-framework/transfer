<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

use Symfony\Component\EventDispatcher\Event;
use Transfer\Adapter\SourceAdapterInterface;
use Transfer\Adapter\Transaction\Request;

/**
 * Event triggered before a source adapter receives a request.
 */
class PreAdapterReceiveEvent extends Event
{
    /**
     * @var SourceAdapterInterface Source adapter
     */
    protected $source;

    /**
     * @var Request Request sent to adapter
     */
    protected $request;

    /**
     * @param SourceAdapterInterface $source  Source adapter
     * @param Request                $request Request sent to adapter
     */
    public function __construct(SourceAdapterInterface $source, Request $request = null)
    {
        $this->source = $source;
        $this->request = $request;
    }

    /**
     * Returns source adapter.
     *
     * @return SourceAdapterInterface Source adapter
     */
    public function getSource()
    {
        return $this->source;
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
