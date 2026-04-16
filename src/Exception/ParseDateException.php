<?php

declare(strict_types=1);

namespace Seoladan\DateTime\Exception;

use DomainException;

final class ParseDateException extends DomainException implements Exception
{
    public function __construct(
        protected string $date,
        protected string $format,
        ?\Throwable $previous = null
    ) {
        parent::__construct(sprintf('Cannot parse date "%s" using format "%s"', $date, $format), previous: $previous);
    }

    public function getDateString(): string
    {
        return $this->date;
    }

    public function getDateFormat(): string
    {
        return $this->format;
    }
}
