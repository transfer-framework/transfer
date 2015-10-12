<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter;

use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Data\ValueObject;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Local file adapter functioning as an input.
 */
class LocalFileAdapter implements InputAdapterInterface
{
    /**
     * @var string Filename
     */
    private $filename;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * {@inheritdoc}
     */
    public function receive(Request $request)
    {
        return new Response(array(
            new ValueObject(file_get_contents($this->filename)),
        ));
    }
}
