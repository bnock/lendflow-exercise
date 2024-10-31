<?php

use Tests\TestCase;

describe('best sellers API filters', function () {
    beforeEach(function () {
        Http::fake([
            '*' => Http::response(
                [
                    'status' => 'OK',
                    'copyright' => 'Copyright (c) 2024 The New York Times Company.  All Rights Reserved.',
                    'num_results' => 1,
                    'results' => [
                        [
                            'title' => '1 Ragged Ridge Road',
                            'description' => null,
                            'contributor' => null,
                            'author' => 'David Adams Richards',
                            'contributor_note' => null,
                            'price' => '0.00',
                            'age_group' => null,
                            'publisher' => null,
                            'isbns' => [
                                [
                                    'isbn10' => '0671003542',
                                    'isbn13' => '9780671003548',
                                ],
                            ],
                        ],
                    ],
                ],
            ),
        ]);
    });

    it('works without filters', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT);

        $response->assertOk();
        $response->assertJsonStructure([
            'result_count',
            'books' => [
                '*' => [
                    'title',
                    'description',
                    'contributor',
                    'author',
                    'contributor_note',
                    'price_cents',
                    'age_group',
                    'publisher',
                    'isbns',
                ]
            ]
        ]);
        $response->assertJsonPath('result_count', 1);
        $response->assertJsonPath('books.0.title', '1 Ragged Ridge Road');
    });

    it('filters by author', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT . '?' . http_build_query(['author' => 'Dave']));

        $response->assertOk();
        $response->assertJsonStructure([
            'result_count',
            'books' => [
                '*' => [
                    'title',
                    'description',
                    'contributor',
                    'author',
                    'contributor_note',
                    'price_cents',
                    'age_group',
                    'publisher',
                    'isbns',
                ]
            ]
        ]);
        $response->assertJsonPath('result_count', 1);
        $response->assertJsonPath('books.0.author', 'David Adams Richards');
    });

    it('filters by title', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT . '?' . http_build_query(['title' => 'Ragged']));

        $response->assertOk();
        $response->assertJsonStructure([
            'result_count',
            'books' => [
                '*' => [
                    'title',
                    'description',
                    'contributor',
                    'author',
                    'contributor_note',
                    'price_cents',
                    'age_group',
                    'publisher',
                    'isbns',
                ]
            ]
        ]);
        $response->assertJsonPath('result_count', 1);
        $response->assertJsonPath('books.0.title', '1 Ragged Ridge Road');
    });

    it('filters by ISBN', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT . '?' . http_build_query(['isbn[]' => '0671003542']));

        $response->assertOk();
        $response->assertJsonStructure([
            'result_count',
            'books' => [
                '*' => [
                    'title',
                    'description',
                    'contributor',
                    'author',
                    'contributor_note',
                    'price_cents',
                    'age_group',
                    'publisher',
                    'isbns',
                ]
            ]
        ]);
        $response->assertJsonPath('result_count', 1);
        $response->assertJsonPath('books.0.isbns.0', '0671003542');
    });

    it('applies an offset', function () {
        $response = $this->getJson(TestCase::API_ENDPOINT . '?' . http_build_query(['offset' => 0]));

        $response->assertOk();
        $response->assertJsonStructure([
            'result_count',
            'books' => [
                '*' => [
                    'title',
                    'description',
                    'contributor',
                    'author',
                    'contributor_note',
                    'price_cents',
                    'age_group',
                    'publisher',
                    'isbns',
                ]
            ]
        ]);
        $response->assertJsonPath('result_count', 1);
        $response->assertJsonPath('books.0.title', '1 Ragged Ridge Road');
    });
});
