<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Console\Command\Manifest;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Transfer\Exception\MissingManifestChainException;
use Transfer\Manifest\ManifestChain;

/**
 * Base for manifest commands.
 */
abstract class ManifestCommand extends Command
{
    /**
     * @var ManifestChain Manifest chain
     */
    protected $chain;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addOption('chain', 'c', InputOption::VALUE_REQUIRED, 'Manifest chain');
    }

    /**
     * Sets manifest chain.
     *
     * @param ManifestChain $chain Manifest chain
     */
    public function setChain(ManifestChain $chain)
    {
        $this->chain = $chain;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getOption('chain');

        if ($file !== null) {
            if (!file_exists($file)) {
                throw new \InvalidArgumentException(sprintf('File "%s" could not be located.', $file));
            }

            /* @var ManifestChain $input */
            $this->chain = require $file;
        }

        if ($this->chain === null) {
            throw new MissingManifestChainException();
        }
    }
}
