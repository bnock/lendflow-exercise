<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Collection;

class NytApiException extends Exception
{
    public function __construct(
        string $message,
        protected ?string $status = null,
        protected ?Collection $errors = null,
        int $code = 0,
        Exception $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getErrors(): ?Collection
    {
        return $this->errors;
    }
}
