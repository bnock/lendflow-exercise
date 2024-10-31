<?php

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

describe('best sellers API errors', function() {
    it('reports an API fault', function () {
        Http::fake([
            '*' => Http::response([
                'fault' => [
                    'faultstring' => 'Failed to resolve API Key variable request.queryparam.api-key',
                    'detail' => [
                        'errorcode' => 'steps.oauth.v2.FailedToResolveAPIKey',
                    ],
                ],
            ], Response::HTTP_UNAUTHORIZED)
        ]);

        $response = $this->getJson(TestCase::API_ENDPOINT);

        $response->assertServerError();
        $response->assertJsonPath('error', 'A NYT API fault occurred');
    });

    it('reports an API error', function () {
        Http::fake([
            '*' => Http::response([
                'status' => 'ERROR',
                'copyright' => 'Copyright (c) 2024 The New York Times Company.  All Rights Reserved.',
                'errors' => [
                    "Parameter offse is not a valid parameter\n",
                    'Bad Request',
                ],
                'results' => [],
            ], Response::HTTP_BAD_REQUEST)
        ]);

        $response = $this->getJson(TestCase::API_ENDPOINT);

        $response->assertServerError();
        $response->assertJsonPath('error', 'A NYT API error occurred');
    });
});
