<?php

namespace AlexJoffroy\Localization\Tests\Concerns;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

trait CreatesRequest
{
    private function createRequest(
        $method = 'GET',
        $content = null,
        $uri = '/',
        $server = [],
        $parameters = [],
        $cookies = [],
        $files = []
    ) {
        return (new Request)->createFromBase(
            SymfonyRequest::create(
                $uri,
                $method,
                $parameters,
                $cookies,
                $files,
                $server,
                $content
            )
        );
    }
}
