<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Thrown when the tenant was correctly resolved but the supplied
 * password did not match. Kept as a distinct exception type from
 * TenantNotFoundException internally (useful for logging/metrics),
 * even though both are surfaced to the end user with the same
 * generic message to avoid leaking which part of the credential pair
 * was wrong.
 */
final class InvalidTenantCredentialsException extends Exception
{
    public static function forEmail(string $email): self
    {
        return new self('The provided credentials are incorrect.');
    }
}
