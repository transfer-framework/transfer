<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Manifest;

use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Transfer\Event\TransferEvents;
use Transfer\Manifest\ManifestInterface;
use Transfer\Manifest\ManifestRunner;
use Transfer\Processor\EventDrivenProcessor;
use Transfer\Processor\SequentialProcessor;

class ManifestRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests run method.
     */
    public function testRun()
    {
        $runner = new ManifestRunner();

        $finished = false;

        /** @var object $manifestProphecy */
        $manifestProphecy = $this->prophesize('Transfer\Manifest\ManifestInterface');
        $manifestProphecy->configureProcedureBuilder(Argument::type('object'))->willReturn(null);
        $manifestProphecy->getProcessor()->willReturn(new SequentialProcessor());
        $manifestProphecy->configureProcessor(Argument::type('object'))->will(function ($args) use (&$finished) {

            /** @var EventDrivenProcessor $processor */
            $processor = $args[0];

            $processor->addListener(TransferEvents::POST_PROCESS, function () use (&$finished) {
                $finished = true;
            });

        });
        /** @var ManifestInterface $manifest */
        $manifest = $manifestProphecy->reveal();

        $runner->setEventDispatcher(new EventDispatcher());
        $runner->run($manifest);

        $this->assertTrue($finished);
    }
}
