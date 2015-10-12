<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Console\Command;

use Transfer\Console\Command\Manifest\ListCommand;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Tester\CommandTester;

class ListCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HelperSet
     */
    private $helperSet;

    /**
     * Set up.
     */
    protected function setUp()
    {
        $this->helperSet = new HelperSet();
        $this->helperSet->set(new TableHelper());
    }

    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        $command = new ListCommand();

        $this->assertEquals('manifest:list', $command->getName());
        $this->assertEquals('List registered manifests', $command->getDescription());
    }

    /**
     * Tests execute method.
     */
    public function testExecute()
    {
        $manifestProphecy = $this->prophesize('Transfer\Manifest\ManifestInterface');
        $manifestProphecy->getName()->willReturn('mock');
        $manifest = $manifestProphecy->reveal();

        $chainProphecy = $this->prophesize('Transfer\Manifest\ManifestChain');
        $chainProphecy->getManifests()->willReturn(array($manifest));
        $chain = $chainProphecy->reveal();

        $command = new ListCommand();
        $command->setHelperSet($this->helperSet);

        $command->setChain($chain);

        $tester = new CommandTester($command);
        $tester->execute(array());

        $expectedDisplay = <<<DISPLAY
+---------------+
| Manifest name |
+---------------+
| mock          |
+---------------+

DISPLAY;

        $this->assertEquals($expectedDisplay, $tester->getDisplay());
    }

    /**
     * Tests execute method on a command with a missing manifest chain.
     */
    public function testExecuteWithMissingChain()
    {
        $command = new ListCommand();
        $command->setHelperSet($this->helperSet);

        $this->setExpectedException('Transfer\Exception\MissingManifestChainException');

        $tester = new CommandTester($command);
        $tester->execute(array());
    }

    /**
     * Tests execute method on a command with a non-existing manifest chain.
     */
    public function testExecuteWithNonExistingChain()
    {
        $command = new ListCommand();
        $command->setHelperSet($this->helperSet);

        $this->setExpectedException('\InvalidArgumentException');

        $tester = new CommandTester($command);
        $tester->execute(array(
            '--chain' => 'non-existing-file',
        ));
    }

    /**
     * Tests execute method on a command with a non-existing manifest chain.
     */
    public function testExecuteWithValidChain()
    {
        $command = new ListCommand();
        $command->setHelperSet($this->helperSet);

        $tester = new CommandTester($command);
        $tester->execute(array(
            '--chain' => __DIR__.'/../../../../../../fixtures/manifest_chain.php',
        ));

        $expectedDisplay = <<<DISPLAY
+---------------+
| Manifest name |
+---------------+

DISPLAY;

        $this->assertEquals($expectedDisplay, $tester->getDisplay());
    }
}
