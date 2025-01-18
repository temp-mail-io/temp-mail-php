<?php

namespace TempMailIo\TempMailPhp\Message\File;

use Psr\Http\Message\StreamInterface;
use TempMailIo\TempMailPhp\Message\Exceptions\CloseFileException;
use TempMailIo\TempMailPhp\Message\Exceptions\OpenFileException;
use TempMailIo\TempMailPhp\Message\Exceptions\WriteFileException;

interface WriterInterface
{
    /**
     * @throws OpenFileException|WriteFileException|CloseFileException
     */
    public function saveFileFromStream(StreamInterface $stream, string $filePathName): void;
}
