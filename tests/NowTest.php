<?php

declare(strict_types=1);

namespace Seoladan\DateTime\Tests;

use Seoladan\DateTime\Now;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Now::class)]
class NowTest extends TestCase
{
    public function testGetDateTime(): void
    {
        $currentTime = new \DateTimeImmutable();

        $this->assertEquals($currentTime, (new Now($currentTime))->getDateTime());
    }

    public function testSetDateTime(): void
    {
        $now = new Now();

        $dateTime = new \DateTimeImmutable('2025-06-12 12:25:00');

        $now->setDateTime($dateTime);

        $this->assertEquals($dateTime, $now->getDateTime());
    }
}
