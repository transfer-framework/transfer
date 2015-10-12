<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Console;

use Symfony\Component\Console\Application;
use Transfer\Console\Command as Commands;

/**
 * Transfer application.
 */
class TransferApplication extends Application
{
    public function __construct()
    {
        parent::__construct('Transfer Command Line Interface', 'v0.3');

        $this->addCommands(array(
            new Commands\Manifest\ListCommand(),
            new Commands\Manifest\RunCommand(),
            new Commands\Manifest\DescribeCommand(),
        ));
    }
}
