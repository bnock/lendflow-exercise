<?php

namespace App\NytApi\DTO;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class Book implements Arrayable
{
    public function __construct(
        protected string $title,
        protected ?string $description,
        protected ?string $contributor,
        protected string $author,
        protected ?string $contributorNote,
        protected int $priceCents,
        protected ?string $ageGroup,
        protected ?string $publisher,
        protected Collection $isbns,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getContributor(): ?string
    {
        return $this->contributor;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getContributorNote(): ?string
    {
        return $this->contributorNote;
    }

    public function getPriceCents(): int
    {
        return $this->priceCents;
    }

    public function getAgeGroup(): ?string
    {
        return $this->ageGroup;
    }

    public function getPublisher(): ?string
    {
        return $this->publisher;
    }

    public function getIsbns(): Collection
    {
        return $this->isbns;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'contributor' => $this->contributor,
            'author' => $this->author,
            'contributor_note' => $this->contributorNote,
            'price_cents' => $this->priceCents,
            'age_group' => $this->ageGroup,
            'publisher' => $this->publisher,
            'isbns' => $this->isbns->toArray(),
        ];
    }
}
