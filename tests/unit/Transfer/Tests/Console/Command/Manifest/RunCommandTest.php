<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Console\Command\Manifest;

use Prophecy\Argument;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Transfer\Console\Command\Manifest\RunCommand;
use Transfer\Processor\SequentialProcessor;

class RunCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        $command = new RunCommand();

        $this->assertEquals('manifest:run', $command->getName());
        $this->assertEquals('Run a specific manifest', $command->getDescription());
    }

    /**
     * Tests execute method.
     */
    public function testExecute()
    {
        $manifestProphecy = $this->prophesize('Transfer\Manifest\ManifestInterface');
        $manifestProphecy->willImplement('Transfer\Manifest\InputDataAwareManifestInterface');
        $manifestProphecy->getName()->willReturn('mock');
        $manifestProphecy->setInputData(null)->willReturn(true);
        $manifestProphecy->configureProcedureBuilder(Argument::type('Transfer\Procedure\ProcedureBuilder'))->shouldBeCalled();
        $manifestProphecy->configureProcessor(Argument::type('Transfer\Processor\ProcessorInterface'))->shouldBeCalled();
        $manifestProphecy->getProcessor()->willReturn(new SequentialProcessor());
        $manifest = $manifestProphecy->reveal();

        $chainProphecy = $this->prophesize('Transfer\Manifest\ManifestChain');
        $chainProphecy->getManifest('mock')->willReturn($manifest);
        $chainProphecy->getManifest('non-existing-manifest')->willReturn(null);
        $chain = $chainProphecy->reveal();

        $command = new RunCommand();
        $command->setChain($chain);
        $command->setEventDispatcher(new EventDispatcher());

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
        $command = new RunCommand();
        $command->setEventDispatcher(new EventDispatcher());

        $this->setExpectedException('Transfer\Exception\MissingManifestChainException');

        $tester = new CommandTester($command);
        $tester->execute(array());
    }
}
