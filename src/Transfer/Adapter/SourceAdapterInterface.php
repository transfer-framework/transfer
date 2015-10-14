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

/**
 * Source adapter interface.
 */
interface SourceAdapterInterface
{
    /**
     * Returns a response based on request.
     *
     * @param Request $request Request to be processed
     *
     * @return Response
     */
    public function receive(Request $request);
}
