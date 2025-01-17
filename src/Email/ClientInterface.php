<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Email;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use TempMailIo\TempMailPhp\Email\Data\Request\CreateRequest;
use TempMailIo\TempMailPhp\Email\Data\Response\CreateResponse;
use TempMailIo\TempMailPhp\Email\Data\Response\DeleteResponse;
use TempMailIo\TempMailPhp\Email\Data\Response\GetMessagesResponse;

interface ClientInterface
{
    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function create(?CreateRequest $createRequest = null): CreateResponse;

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function getMessages(string $email): GetMessagesResponse;

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function delete(string $email): DeleteResponse;
}
