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
use Symfony\Component\Console\Output\OutputInterface;
use Transfer\Manifest\ManifestInterface;

/**
 * Command for listing manifests in manifest chain.
 */
class ListCommand extends ManifestCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('manifest:list')
            ->setDescription('List registered manifests');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $manifests = $this->chain->getManifests();

        $rows = array();

        /** @var ManifestInterface $manifest */
        foreach ($manifests as $manifest) {
            $rows[] = array($manifest->getName());
        }

        $table = $this->getHelper('table');

        $table->setHeaders(array('Manifest name'))
              ->setRows($rows);

        $table->render($output);
    }
}
