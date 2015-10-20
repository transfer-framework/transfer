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

abstract class NonDirectionalAdapter implements SourceAdapterInterface, TargetAdapterInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    abstract public function handle(Request $request);

    /**
     * {@inheritdoc}
     */
    public function receive(Request $request)
    {
        return $this->handle($request);
    }

    /**
     * {@inheritdoc}
     */
    public function send(Request $request)
    {
        return $this->handle($request);
    }
}
