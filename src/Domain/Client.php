<?php

declare(strict_types=1);

namespace TempMailIo\TempMailPhp\Domain;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use TempMailIo\TempMailPhp\Constants;
use TempMailIo\TempMailPhp\Domain\Data\Response\GetAvailableDomainResponse;
use TempMailIo\TempMailPhp\Domain\Data\Response\GetAvailableDomainsSuccessResponse;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;
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
    public function getAvailableDomains(): GetAvailableDomainResponse
    {
        $getAvailableDomainsResponse = GetAvailableDomainResponse::create();

        try {
            $response = $this->guzzleClient->request('GET', Constants::API_V1_URL . '/domains', [
                'headers' => [
                    Constants::API_KEY_HEADER => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $getAvailableDomainsResponse->successResponse = GetAvailableDomainsSuccessResponse::create()
                    ->fromArray(json_decode($response->getBody()->getContents(), true));
                $getAvailableDomainsResponse->successResponse->rateLimit = $this->rateLimitReader->createRateLimitFromHeaders($response->getHeaders());

                return $getAvailableDomainsResponse;
            }
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $getAvailableDomainsResponse->errorResponse = ErrorResponse::create()
                ->fromArray(json_decode($response->getBody()->getContents(), true));

            return $getAvailableDomainsResponse;
        }

        return $getAvailableDomainsResponse;
    }
}
