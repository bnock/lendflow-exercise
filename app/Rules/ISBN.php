<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ISBN implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) !== 10 && strlen($value) !== 13) {
            $fail('ISBN values must be 10 or 13 characters in length');
        }

        if (!is_numeric($value)) {
            $fail('ISBN values must be numeric');
        }
    }
}
