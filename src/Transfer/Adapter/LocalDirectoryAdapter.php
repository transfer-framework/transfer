<?php

/*
 * This file is part of Transfer.
 *
 * For the full copyright and license information, please view the LICENSE file located
 * in the root directory.
 */

namespace Transfer\Adapter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Transfer\Adapter\Transaction\Iterator\CallbackIterator;
use Transfer\Adapter\Transaction\Request;
use Transfer\Adapter\Transaction\Response;
use Transfer\Data\ValueObject;

/**
 * Local directory adapter functioning as a source.
 */
class LocalDirectoryAdapter implements SourceAdapterInterface
{
    /**
     * @var array Option collection
     */
    private $options;

    /**
     * @var string Directory to read
     */
    private $directory;

    /**
     * @var array Found files
     */
    private $fileNames;

    /**
     * @param array $options Options
     */
    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * Configures options.
     *
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('directory'));
        $resolver->setAllowedTypes('directory', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function receive(Request $request)
    {
        $this->directory = $this->options['directory'];
        $this->fileNames = array_values(array_diff(scandir($this->directory), array('..', '.')));

        $response = new Response(
            new CallbackIterator(array($this, 'validCallback'), array($this, 'currentCallback'))
        );

        return $response;
    }

    /**
     * Checks whether there are more files left.
     *
     * @param CallbackIterator $iterator
     *
     * @return bool
     */
    public function validCallback(CallbackIterator $iterator)
    {
        return (bool) array_key_exists($iterator->key(), $this->fileNames);
    }

    /**
     * Returns a file object.
     *
     * @param CallbackIterator $iterator
     *
     * @return ValueObject
     */
    public function currentCallback(CallbackIterator $iterator)
    {
        $clientFilename = $this->fileNames[$iterator->key()];
        $filename = $this->directory.DIRECTORY_SEPARATOR.$clientFilename;

        $fileObject = new ValueObject(file_get_contents($filename));
        $fileObject->setProperty('filename', $filename);
        $fileObject->setProperty('client_filename', $clientFilename);

        return $fileObject;
    }
}
