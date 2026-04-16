<?php

declare(strict_types=1);

namespace Seoladan\DateTime;

use DateTimeImmutable;

class Now
{
    public function __construct(
        private DateTimeImmutable $dateTime = new DateTimeImmutable()
    ) {
    }

    /**
     * @param DateTimeImmutable $now
     * @return void
     * @internal Facilitiates testing by allowing 'now' to be overridden
     */
    public function setDateTime(DateTimeImmutable $now = new DateTimeImmutable()): void {
        $this->dateTime = $now;
    }

    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }
}
