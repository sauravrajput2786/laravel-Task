<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

/**
 * Thrown when an email address cannot be mapped to any known tenant,
 * or a client_code does not correspond to an active client. Deliberately
 * generic in its public message so as not to confirm/deny which emails
 * exist in the system (user enumeration protection) - see
 * AuthenticationService for how this is surfaced to the end user.
 */
final class TenantNotFoundException extends Exception
{
    public static function forEmail(string $email): self
    {
        return new self('We could not find an account associated with the provided credentials.');
    }

    public static function forClientCode(string $clientCode): self
    {
        return new self('We could not find an account associated with the provided credentials.');
    }
}
