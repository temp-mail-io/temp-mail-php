<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Email;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use TempMailIo\TempMailPhp\Constants;
use TempMailIo\TempMailPhp\Email\Data\Request\CreateRequest;
use TempMailIo\TempMailPhp\Email\Data\Response\CreateResponse;
use TempMailIo\TempMailPhp\Email\Data\Response\CreateSuccessResponse;
use TempMailIo\TempMailPhp\Email\Data\Response\DeleteResponse;
use TempMailIo\TempMailPhp\Email\Data\Response\GetMessagesResponse;
use TempMailIo\TempMailPhp\Email\Data\Response\GetMessagesSuccessResponse;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;
use TempMailIo\TempMailPhp\GenericData\RateLimit;
use TempMailIo\TempMailPhp\GenericData\SuccessResponse;
use TempMailIo\TempMailPhp\RateLimitReader;

class Client implements ClientInterface
{
    public function __construct(
        private readonly GuzzleClientInterface $guzzleClient,
        private readonly RateLimitReader       $rateLimitReader,
        private readonly string                $apiKey,
    ) {
    }

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function create(?CreateRequest $createRequest): CreateResponse
    {
        if ($createRequest === null) {
            $createRequest = new CreateRequest();
        }

        $createResponse = CreateResponse::create();

        try {
            $response = $this->guzzleClient->request('POST', Constants::API_V1_URL . '/emails', [
                'headers' => [
                    Constants::API_KEY_HEADER => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'json' => $createRequest->toArray(),
            ]);

            if ($response->getStatusCode() === 200) {
                $createResponse->successResponse = CreateSuccessResponse::create()
                    ->fromArray(json_decode($response->getBody()->getContents(), true));
                $createResponse->successResponse->rateLimit = $this->rateLimitReader->createRateLimitFromHeaders($response->getHeaders());

                return $createResponse;
            }
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $createResponse->errorResponse = ErrorResponse::create()
                ->fromArray(json_decode($response->getBody()->getContents(), true));

            return $createResponse;
        }

        return $createResponse;
    }

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function getMessages(string $email): GetMessagesResponse
    {
        $getMessagesResponse = GetMessagesResponse::create();

        try {
            $response = $this->guzzleClient->request('GET', Constants::API_V1_URL . "/emails/{$email}/messages", [
                'headers' => [
                    Constants::API_KEY_HEADER => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $getMessagesResponse->successResponse = GetMessagesSuccessResponse::create()
                    ->fromArray(json_decode($response->getBody()->getContents(), true));
                $getMessagesResponse->successResponse->rateLimit = $this->rateLimitReader->createRateLimitFromHeaders($response->getHeaders());

                return $getMessagesResponse;
            }
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $getMessagesResponse->errorResponse = ErrorResponse::create()
                ->fromArray(json_decode($response->getBody()->getContents(), true));

            return $getMessagesResponse;
        }

        return $getMessagesResponse;
    }

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function delete(string $email): DeleteResponse
    {
        $deleteResponse = DeleteResponse::create();

        try {
            $response = $this->guzzleClient->request('DELETE', Constants::API_V1_URL . "/emails/{$email}", [
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
}
