<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Console\Command\Manifest;

use Transfer\Exception\ManifestNotFoundException;
use Transfer\Manifest\ManifestInterface;
use Transfer\Procedure\Procedure;
use Transfer\Procedure\ProcedureBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for describing a manifest in a manifest chain.
 */
class DescribeCommand extends ManifestCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('manifest:describe')
            ->setDescription('Describe a specific manifest')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of the manifest')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $name = $input->getArgument('name');

        if (!($manifest = $this->chain->getManifest($name))) {
            throw new ManifestNotFoundException($name);
        }

        $this->describeManifest($manifest, $output);
    }

    /**
     * Describes a manifest.
     *
     * @param ManifestInterface $manifest Manifest
     * @param OutputInterface   $output   Output
     */
    private function describeManifest(ManifestInterface $manifest, OutputInterface $output)
    {
        $builder = new ProcedureBuilder();
        $manifest->configureProcedureBuilder($builder);

        $output->writeln(sprintf("Manifest description for <info>%s</info>\n", $manifest->getName()));
        $output->writeln("<fg=blue;options=bold>PROCEDURE CONFIGURATION</fg=blue;options=bold>\n");

        $this->describeProcedure($builder->getProcedure(), $output);
    }

    /**
     * Describes a procedure.
     *
     * @param Procedure       $procedure Procedure
     * @param OutputInterface $output    Console output handle
     * @param int             $depth     Current depth
     */
    private function describeProcedure(Procedure $procedure, OutputInterface $output, $depth = 0)
    {
        $output->writeln(sprintf('%sProcedure <info>%s</info>', str_repeat(' ', $depth), $procedure->getName()));

        $this->describeComponents($procedure->getInputs(), 'INPUTS', $output, $depth);
        $this->describeComponents($procedure->getWorkers(), 'WORKERS', $output, $depth);
        $this->describeComponents($procedure->getOutputs(), 'OUTPUTS', $output, $depth);
        $this->describeChildProcedures($procedure->getChildren(), $output, $depth);

        $output->write("\n");
    }

    /**
     * Describes child procedures.
     *
     * @param array           $children Procedure childnre
     * @param OutputInterface $output   Console output handle
     * @param int             $depth    Current depth
     */
    private function describeChildProcedures($children, $output, $depth)
    {
        if (is_array($children)) {
            $output->writeln(sprintf('%s<comment>SUB-PROCEDURES</comment>', str_repeat(' ', $depth)));

            foreach ($children as $child) {
                $this->describeProcedure($child, $output, $depth + 2);
                $output->writeln('');
            }
        }
    }

    /**
     * Describes components.
     *
     * @param array           $components Components
     * @param string          $name       Component type
     * @param OutputInterface $output     Console output handle
     * @param int             $depth      Current depth
     */
    private function describeComponents($components, $name, OutputInterface $output, $depth = 0)
    {
        if (is_array($components)) {
            $output->writeln(sprintf('%s<comment>%s</comment>', str_repeat(' ', $depth), $name));

            foreach ($components as $component) {
                $output->writeln(sprintf(
                    '%s%s',
                    str_repeat(' ', $depth + 4),
                    is_object($component) ? get_class($component) : get_class($component[0])
                ));
            }
        }
    }
}
