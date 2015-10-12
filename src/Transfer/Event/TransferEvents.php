<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Event;

/**
 * Transfer event enumeration.
 */
class TransferEvents
{
    /**
     * Event triggered before a procedure is started.
     */
    const PRE_PROCEDURE = 'transfer.pre_procedure';

    /**
     * Event triggered after a procedure is finished.
     */
    const POST_PROCEDURE = 'transfer.post_procedure';

    /**
     * Event triggered before an input adapter receives a request.
     */
    const PRE_ADAPTER_RECEIVE = 'transfer.pre_adapter_receive';

    /**
     * Event triggered after an input adapter returns a response.
     */
    const POST_ADAPTER_RECEIVE = 'transfer.post_adapter_receive';

    /**
     * Event triggered before an object is passed through a worker.
     */
    const PRE_WORKER = 'transfer.pre_worker';

    /**
     * Event triggered after an object is passed through a worker.
     */
    const POST_WORKER = 'transfer.post_worker';

    /**
     * Event triggered before an output adapter receives a request.
     */
    const PRE_ADAPTER_SEND = 'transfer.pre_adapter_send';

    /**
     * Event triggered after an output adapter returns a response.
     */
    const POST_ADAPTER_SEND = 'transfer.post_adapter_send';

    /**
     * Event triggered before a process is started.
     */
    const PRE_PROCESS = 'transfer.pre_process';

    /**
     * Event triggered after a process is finished.
     */
    const POST_PROCESS = 'transfer.post_process';
}
