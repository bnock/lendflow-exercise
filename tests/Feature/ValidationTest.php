<?php

use Tests\TestCase;

describe('best sellers API filter validation', function () {
    it('reports invalid length ISBN', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT . '?' . http_build_query(['isbn[]' => '012345678']));

        $response->assertStatus(422);
        $response->assertInvalid([
            'isbn.0' => 'ISBN values must be 10 or 13 characters in length',
        ]);
    });

    it('reports invalid ISBN with non-numeric characters', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT . '?' . http_build_query(['isbn[]' => '0123ABCD89']));

        $response->assertStatus(422);
        $response->assertInvalid([
            'isbn.0' => 'ISBN values must be numeric',
        ]);
    });

    it('reports invalid non-integer offset', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT . '?' . http_build_query(['offset' => '20A']));

        $response->assertStatus(422);
        $response->assertInvalid([
            'offset' => 'The offset field must be an integer.',
        ]);
    });

    it('reports invalid offset that is not a multiple of 20', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT . '?' . http_build_query(['offset' => 21]));

        $response->assertStatus(422);
        $response->assertInvalid([
            'offset' => 'The offset field must be a multiple of 20.',
        ]);
    });
});
