<?php

namespace TempMailIo\TempMailPhp\Message\File;

use Psr\Http\Message\StreamInterface;
use TempMailIo\TempMailPhp\Message\Exceptions\CloseFileException;
use TempMailIo\TempMailPhp\Message\Exceptions\OpenFileException;
use TempMailIo\TempMailPhp\Message\Exceptions\WriteFileException;

class Writer implements WriterInterface
{
    /**
     * @throws OpenFileException|WriteFileException|CloseFileException
     */
    public function saveFileFromStream(StreamInterface $stream, string $filePathName): void
    {
        $destination = fopen($filePathName, 'w');

        if ($destination === false) {
            $lastError = error_get_last();

            throw new OpenFileException($lastError['message'] ?? '', $lastError['type'] ?? 0);
        }

        while (!$stream->eof()) {
            $writeResult = fwrite($destination, $stream->read(1024));

            if ($writeResult === false) {
                $lastError = error_get_last();

                throw new WriteFileException($lastError['message'] ?? '', $lastError['type'] ?? 0);
            }
        }

        $closeResult = fclose($destination);

        if ($closeResult === false) {
            $lastError = error_get_last();

            throw new CloseFileException($lastError['message'] ?? '', $lastError['type'] ?? 0);
        }
    }
}
