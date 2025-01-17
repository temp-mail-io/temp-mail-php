<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Message;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use TempMailIo\TempMailPhp\Message\Data\Response\DeleteResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\DownloadAttachmentResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\GetMessageResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\GetMessageSourceCodeResponse;
use TempMailIo\TempMailPhp\Message\Exceptions\CloseFileException;
use TempMailIo\TempMailPhp\Message\Exceptions\OpenFileException;
use TempMailIo\TempMailPhp\Message\Exceptions\WriteFileException;

interface ClientInterface
{
    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function getMessage(string $id): GetMessageResponse;

    public function getMessageSourceCode(string $id): GetMessageSourceCodeResponse;

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function delete(string $id): DeleteResponse;

    /**
     * @throws GuzzleException|\ReflectionException|ServerException|OpenFileException|WriteFileException|CloseFileException
     */
    public function downloadAttachment(string $id, string $filePathName): DownloadAttachmentResponse;
}
