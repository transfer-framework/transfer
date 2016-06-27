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

class SingleBufferAdapter implements SourceAdapterInterface, TargetAdapterInterface
{
    /**
     * @var mixed Buffer
     */
    private $buffer;

    /**
     * {@inheritdoc}
     */
    public function receive(Request $request)
    {
        return new Response($this->buffer);
    }

    /**
     * {@inheritdoc}
     */
    public function send(Request $request)
    {
        $this->buffer = $request->getData();

        return new Response();
    }
}
