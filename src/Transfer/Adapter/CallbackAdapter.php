<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter;

use Transfer\Adapter\Transaction\Request;

/**
 * Source and target adapter implementation with callback support.
 */
class CallbackAdapter implements SourceAdapterInterface, TargetAdapterInterface
{
    /**
     * @var callback Receive callback
     */
    protected $receiveCallback;

    /**
     * @var callback Send callback
     */
    protected $sendCallback;

    /**
     * @param callback|null $receiveCallback Receive callback
     * @param callback|null $sendCallback    Send callback
     */
    public function __construct($receiveCallback = null, $sendCallback = null)
    {
        $this->receiveCallback = $receiveCallback;
        $this->sendCallback = $sendCallback;
    }

    /**
     * {@inheritdoc}
     */
    public function receive(Request $request)
    {
        if (is_callable($this->receiveCallback)) {
            return call_user_func_array($this->receiveCallback, array($request));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send(Request $request)
    {
        if (is_callable($this->sendCallback)) {
            return call_user_func_array($this->sendCallback, array($request));
        }
    }
}
