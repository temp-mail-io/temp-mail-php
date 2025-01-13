<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Message;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use TempMailIo\TempMailPhp\Constants;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;
use TempMailIo\TempMailPhp\GenericData\SuccessResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\DeleteResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\DownloadAttachmentResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\DownloadAttachmentSuccessResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\GetMessageResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\GetMessageSourceCodeResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\GetMessageSourceCodeSuccessResponse;
use TempMailIo\TempMailPhp\Message\Data\Response\GetMessageSuccessResponse;
use TempMailIo\TempMailPhp\RateLimitReaderInterface;

class Client implements ClientInterface
{
    public function __construct(
        private readonly GuzzleClientInterface    $guzzleClient,
        private readonly RateLimitReaderInterface $rateLimitReader,
        private readonly string                   $apiKey,
    )
    {
    }

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function getMessage(string $id): GetMessageResponse
    {
        $getMessageResponse = GetMessageResponse::create();

        try {
            $response = $this->guzzleClient->request('GET', Constants::API_V1_URL . "/messages/{$id}", [
                'headers' => [
                    Constants::API_KEY_HEADER => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $getMessageResponse->successResponse = GetMessageSuccessResponse::create()
                    ->fromArray(['message' => json_decode($response->getBody()->getContents(), true)]);
                $getMessageResponse->successResponse->rateLimit = $this->rateLimitReader->createRateLimitFromHeaders($response->getHeaders());

                return $getMessageResponse;
            }
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $getMessageResponse->errorResponse = ErrorResponse::create()
                ->fromArray(json_decode($response->getBody()->getContents(), true));

            return $getMessageResponse;
        }

        return $getMessageResponse;
    }

    public function getMessageSourceCode(string $id): GetMessageSourceCodeResponse
    {
        $getMessageSourceCodeResponse = GetMessageSourceCodeResponse::create();

        try {
            $response = $this->guzzleClient->request('GET', Constants::API_V1_URL . "/messages/{$id}/source_code", [
                'headers' => [
                    Constants::API_KEY_HEADER => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $getMessageSourceCodeResponse->successResponse = GetMessageSourceCodeSuccessResponse::create()
                    ->fromArray(json_decode($response->getBody()->getContents(), true));
                $getMessageSourceCodeResponse->successResponse->rateLimit = $this->rateLimitReader->createRateLimitFromHeaders($response->getHeaders());

                return $getMessageSourceCodeResponse;
            }
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $getMessageSourceCodeResponse->errorResponse = ErrorResponse::create()
                ->fromArray(json_decode($response->getBody()->getContents(), true));

            return $getMessageSourceCodeResponse;
        }

        return $getMessageSourceCodeResponse;
    }

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function delete(string $id): DeleteResponse
    {
        $deleteResponse = DeleteResponse::create();

        try {
            $response = $this->guzzleClient->request('DELETE', Constants::API_V1_URL . "/messages/{$id}", [
                'headers' => [
                    Constants::API_KEY_HEADER => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $deleteResponse->successResponse = SuccessResponse::create();
                $deleteResponse->successResponse->rateLimit = $this->rateLimitReader->createRateLimitFromHeaders($response->getHeaders());

                return $deleteResponse;
            }
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $deleteResponse->errorResponse = ErrorResponse::create()
                ->fromArray(json_decode($response->getBody()->getContents(), true));

            return $deleteResponse;
        }

        return $deleteResponse;
    }

    public function downloadAttachment(string $id): DownloadAttachmentResponse
    {
        $downloadAttachmentResponse = DownloadAttachmentResponse::create();

        try {
            $response = $this->guzzleClient->request('GET', Constants::API_V1_URL . "/attachments/{$id}", [
                'headers' => [
                    Constants::API_KEY_HEADER => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $downloadAttachmentResponse->successResponse = DownloadAttachmentSuccessResponse::create()
                    ->fromArray(json_decode($response->getBody()->getContents(), true));
                $downloadAttachmentResponse->successResponse->rateLimit = $this->rateLimitReader->createRateLimitFromHeaders($response->getHeaders());

                return $downloadAttachmentResponse;
            }
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $downloadAttachmentResponse->errorResponse = ErrorResponse::create()
                ->fromArray(json_decode($response->getBody()->getContents(), true));

            return $downloadAttachmentResponse;
        }

        return $downloadAttachmentResponse;
    }
}
