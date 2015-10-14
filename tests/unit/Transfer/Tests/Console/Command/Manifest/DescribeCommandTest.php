<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Console\Command;

use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;
use Transfer\Console\Command\Manifest\DescribeCommand;
use Transfer\Procedure\ProcedureBuilder;

class DescribeCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        $command = new DescribeCommand();

        $this->assertEquals('manifest:describe', $command->getName());
        $this->assertEquals('Describe a specific manifest', $command->getDescription());
    }

    /**
     * Tests execute method.
     */
    public function testExecute()
    {
        $source = $this->getMock('Transfer\Adapter\SourceAdapterInterface');
        $worker = $this->getMock('Transfer\Worker\WorkerInterface');
        $target = $this->getMock('Transfer\Adapter\TargetAdapterInterface');

        $manifestProphecy = $this->prophesize('Transfer\Manifest\ManifestInterface');
        $manifestProphecy->getName()->willReturn('mock');
        $manifestProphecy->configureProcedureBuilder(Argument::type('Transfer\Procedure\ProcedureBuilder'))->will(
            function ($args) use ($source, $worker, $target) {
                /** @var ProcedureBuilder $builder */
                $builder = $args[0];
                $builder
                    ->createProcedure('level_1')
                        ->addSource($source)
                        ->addWorker($worker)
                        ->addTarget($target)
                    ->end();
            }
        );
        $manifest = $manifestProphecy->reveal();

        $chainProphecy = $this->prophesize('Transfer\Manifest\ManifestChain');
        $chainProphecy->getManifest('mock')->willReturn($manifest);
        $chainProphecy->getManifest('non-existing-manifest')->willReturn(null);
        $chain = $chainProphecy->reveal();

        $command = new DescribeCommand();
        $command->setChain($chain);

        $tester = new CommandTester($command);
        $tester->execute(array('name' => 'mock'));

        $this->setExpectedException('Transfer\Exception\ManifestNotFoundException');
        $tester->execute(array('name' => 'non-existing-manifest'));
    }

    /**
     * Tests execute method on a command with a missing manifest chain.
     */
    public function testExecuteWithMissingChain()
    {
        $command = new DescribeCommand();

        $this->setExpectedException('Transfer\Exception\MissingManifestChainException');

        $tester = new CommandTester($command);
        $tester->execute(array('name' => 'none'));
    }
}
