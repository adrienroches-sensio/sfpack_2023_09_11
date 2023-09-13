<?php

declare(strict_types=1);

namespace App\Omdb\Bridge\Command;

use Throwable;

final class Exception extends \Exception
{
    public const REASON_NO_SELECT = 'no_select';

    private function __construct(
        string $message = "",
        public readonly string|null $reason = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function nothingToSelect(Throwable|null $previous = null): self
    {
        return new self(
            'None of the candidates were selected.',
            reason: self::REASON_NO_SELECT,
            previous: $previous,
        );
    }
}
