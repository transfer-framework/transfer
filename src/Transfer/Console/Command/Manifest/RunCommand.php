<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Console\Command\Manifest;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Transfer\Exception\ManifestNotFoundException;
use Transfer\Manifest\InputDataAwareManifestInterface;
use Transfer\Manifest\ManifestInterface;
use Transfer\Manifest\ManifestRunner;

/**
 * Command for running a manifest.
 */
class RunCommand extends ManifestCommand
{
    /**
     * @var EventDispatcherInterface Custom event dispatcher
     */
    protected $dispatcher;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('manifest:run')
            ->setDescription('Run a specific manifest')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of the manifest')
            ->addOption('input', 'i', InputOption::VALUE_OPTIONAL, 'Input data')
        ;
    }

    /**
     * Sets custom event dispatcher.
     *
     * @param EventDispatcherInterface $dispatcher Custom event dispatcher
     */
    public function setEventDispatcher($dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $name = $input->getArgument('name');

        $manifest = $this->chain->getManifest($name);

        if (!$manifest) {
            throw new ManifestNotFoundException($name);
        }

        $this->applyInputData($manifest, $input->getOption('input'));

        $this->runManifest($manifest);
    }

    /**
     * Runs a manifest.
     *
     * @param ManifestInterface $manifest Manifest to run
     */
    private function runManifest(ManifestInterface $manifest)
    {
        $runner = new ManifestRunner();
        $runner->run($manifest);
    }

    /**
     * Applies input data.
     *
     * @param ManifestInterface $manifest Manifest
     * @param string            $input    Input data
     */
    private function applyInputData(ManifestInterface $manifest, $input)
    {
        if ($manifest instanceof InputDataAwareManifestInterface) {
            $manifest->setInputData($input);
        }
    }
}
