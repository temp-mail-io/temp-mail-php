<?php declare(strict_types=1);

namespace TempMailIo\TempMailPhp\RateLimit;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use TempMailIo\TempMailPhp\Constants;
use TempMailIo\TempMailPhp\RateLimit\Data\Response\GetStatusResponse;
use TempMailIo\TempMailPhp\GenericData\ErrorResponse;
use TempMailIo\TempMailPhp\RateLimit\Data\Response\GetStatusSuccessResponse;

class Client implements ClientInterface
{
    public function __construct(
        private readonly GuzzleClientInterface $guzzleClient,
        private readonly string                $apiKey,
    )
    {
    }

    /**
     * @throws GuzzleException|\ReflectionException|ServerException
     */
    public function getStatus(): GetStatusResponse
    {
        $getStatusResponse = GetStatusResponse::create();

        try {
            $response = $this->guzzleClient->request('GET', Constants::API_V1_URL . '/rate_limit', [
                'headers' => [
                    Constants::API_KEY_HEADER => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $getStatusResponse->successResponse = GetStatusSuccessResponse::create()
                    ->fromArray(json_decode($response->getBody()->getContents(), true));

                return $getStatusResponse;
            }
        } catch (ClientException $exception) {
            $response = $exception->getResponse();

            $getStatusResponse->errorResponse = ErrorResponse::create()
                ->fromArray(json_decode($response->getBody()->getContents(), true));

            return $getStatusResponse;
        }

        return $getStatusResponse;
    }
}