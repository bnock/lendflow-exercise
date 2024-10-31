<?php

namespace App\NytApi\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use JsonException;
use JsonSerializable;

class BooksResult implements Jsonable, JsonSerializable, Arrayable
{
    public function __construct(protected int $resultCount, protected Collection $books)
    {
    }

    public function getResultCount(): int
    {
        return $this->resultCount;
    }

    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function toArray(): array
    {
        return [
            'result_count' => $this->resultCount,
            'books' => $this->books->toArray(),
        ];
    }

    /**
     * @throws JsonException
     */
    public function toJson($options = 0): false|string
    {
        return json_encode($this->jsonSerialize(), $options | JSON_THROW_ON_ERROR);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
