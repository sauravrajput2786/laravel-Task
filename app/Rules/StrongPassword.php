<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Enforces a minimum password complexity: at least 8 characters, with
 * a mix of upper/lowercase letters and at least one number. Applied
 * wherever a user's password is being *set* (not on the login form
 * itself, where we only require presence - validating strength at
 * login time would let an attacker use the error message to guess
 * whether a discovered password merely fails the complexity policy).
 */
final class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || mb_strlen($value) < 8) {
            $fail('The :attribute must be at least 8 characters long.');

            return;
        }

        if (! preg_match('/[A-Z]/', $value) || ! preg_match('/[a-z]/', $value)) {
            $fail('The :attribute must contain both upper and lower case letters.');

            return;
        }

        if (! preg_match('/[0-9]/', $value)) {
            $fail('The :attribute must contain at least one number.');
        }
    }
}
