<?php

namespace Tests;

use Psr\Http\Message\RequestInterface;

class HeadersHelper
{
    public static function getHeadersFromRequest(RequestInterface $request): array
    {
        return [
            'Host' => $request->getHeaders()['Host'][0],
            'X-API-Key' => $request->getHeaders()['X-API-Key'][0],
            'Content-Type' => $request->getHeaders()['Content-Type'][0],
            'Accept' => $request->getHeaders()['Accept'][0],
            'User-Agent' => $request->getHeaders()['User-Agent'][0],
        ];
    }
}
