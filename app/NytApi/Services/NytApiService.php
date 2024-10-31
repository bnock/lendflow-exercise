<?php

namespace App\NytApi\Services;

use App\Exceptions\NytApiException;
use App\NytApi\DTO\Book;
use App\NytApi\DTO\BooksResult;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NytApiService
{
    protected const BEST_SELLERS_ENDPOINT = 'https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json';

    /**
     * Find books via the NYT API.
     *
     * @param string|null $author
     * @param Collection|null $isbns
     * @param string|null $title
     * @param int $offset
     * @return BooksResult
     * @throws NytApiException
     */
    public function findBooks(
        string $author = null,
        Collection $isbns = null,
        string $title = null,
        int $offset = 0,
    ): BooksResult {
        $queryParams = [
            'api-key' => config('nyt-api.api-key'),
            'offset' => $offset,
        ];

        if (filled($author)) {
            $queryParams['author'] = $author;
        }

        if (filled($isbns) && $isbns->isNotEmpty()) {
            $queryParams['isbn'] = $isbns->join(';'); // ISBNs are sent as a semicolon-delimited string
        }

        if (filled($title)) {
            $queryParams['title'] = $title;
        }

        $response = Http::get(static::BEST_SELLERS_ENDPOINT, $queryParams);

        $this->validateBooksResponse($response);

        return $this->parseBooksResponse($response);
    }

    /**
     * Validate the NYT API books response.
     *
     * @param Response|PromiseInterface $response
     * @return void
     * @throws NytApiException
     */
    protected function validateBooksResponse(Response|PromiseInterface $response): void
    {
        $status = $response->json('status', '');

        if (Str::lower($status) !== 'ok') {
            if (blank($status) && filled($fault = $response->json('fault'))) {
                throw new NytApiException('A NYT API fault occurred', 'fault', collect([$fault['faultstring'] ?? null]));
            } elseif (Str::lower($status) === 'error') {
                throw new NytApiException('A NYT API error occurred', $status, collect($response->json('errors', [])));
            } else {
                throw new NytApiException('An unknown NYT API error occurred');
            }
        }
    }

    /**
     * Parse a successful books response.
     *
     * @param Response|PromiseInterface $response
     * @return BooksResult
     */
    protected function parseBooksResponse(Response|PromiseInterface $response): BooksResult
    {
        $resultCount = $response->json('num_results');

        $books = collect($response->json('results', []))->map(function (array $bookData) {
            $isbns = collect();

            if (filled($isbn10 = $bookData['isbns'][0]['isbn10'] ?? null)) {
                $isbns->push($isbn10);
            }

            if (filled($isbn13 = $bookData['isbns'][0]['isbn13'] ?? null)) {
                $isbns->push($isbn13);
            }

            return new Book(
                $bookData['title'],
                $bookData['description'] ?? null,
                $bookData['contributor'] ?? null,
                $bookData['author'],
                $bookData['contributor_note'] ?? null,
                intval($bookData['price'] * 100),
                $bookData['age_group'] ?? null,
                $bookData['publisher'],
                $isbns,
            );
        });

        return new BooksResult($resultCount, $books);
    }
}
