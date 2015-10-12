<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Tests\Adapter;

use Transfer\Adapter\LocalDirectoryAdapter;
use Transfer\Adapter\Transaction\Request;

class LocalDirectoryAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests constructor.
     */
    public function testConstructor()
    {
        new LocalDirectoryAdapter(array(
            'directory' => __DIR__.'../../../../fixtures',
        ));

        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException');
        new LocalDirectoryAdapter(array(
            'directory' => null,
        ));
    }

    /**
     * Tests receive method.
     */
    public function testReceive()
    {
        $adapter = new LocalDirectoryAdapter(array(
            'directory' => __DIR__.'/../../../../fixtures/fixed_directory',
        ));

        $response = $adapter->receive(new Request());
        $this->assertInstanceOf('Transfer\Adapter\Transaction\Response', $response);

        $expected = array(
            '1.txt',
            '2.txt',
        );

        foreach ($response as $index => $object) {
            $this->assertInstanceOf('Transfer\Data\ValueObject', $object);
            $this->assertEquals($expected[$index], $object->getProperty('client_filename'));
        }
    }
}
