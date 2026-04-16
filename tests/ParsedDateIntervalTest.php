<?php

declare(strict_types=1);

namespace Seoladan\DateTime\Tests;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\DateTime\DateRoundingMode;
use Seoladan\DateTime\DateRoundingUnit;
use Seoladan\DateTime\ParsedDateInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ParsedDateInterval::class)]
#[UsesClass(DateRoundingMode::class)]
#[UsesClass(DateRoundingUnit::class)]
class ParsedDateIntervalTest extends TestCase
{
    public static function relativeToProvider(): array
    {
        return [
            [
                null,
            ],
            [
                new DateTimeImmutable(),
            ]
        ];
    }

    #[DataProvider('relativeToProvider')]
    public function testGetRelativeTo(?DateTimeImmutable $dateTime): void {
        $result = new ParsedDateInterval('1 day', [], $dateTime);

        $this->assertEquals($dateTime, $result->getRelativeTo());
    }

    public function testGetIntervalString() {
        $result = new ParsedDateInterval('1 day', []);

        $this->assertEquals('1 day', $result->getIntervalString());
    }

    public function testGetParsedIntervals() {
        $intervals = [
            new DateInterval('P1D'),
        ];

        $result = new ParsedDateInterval('1 day', $intervals);

        $this->assertEquals($intervals, $result->getParsedIntervals());
    }

    public static function intervalsProvider(): array
    {
        return [
            [
                '+1 day',
                [
                    DateInterval::createFromDateString('+1 day'),
                ],
                DateRoundingMode::None,
                new DateTimeImmutable('2026-04-10 20:24:25'),
                new DateTimeImmutable('2026-04-11 20:24:25')
            ],
            [
                '+1 day +1 month',
                [
                    DateInterval::createFromDateString('+1 day'),
                    DateInterval::createFromDateString('+1 month'),
                ],
                DateRoundingMode::None,
                new DateTimeImmutable('2026-04-10 20:24:25'),
                new DateTimeImmutable('2026-05-11 20:24:25')
            ],
            [
                '+1 month -1 day',
                [
                    DateInterval::createFromDateString('+1 month'),
                    DateInterval::createFromDateString('-1 day'),
                ],
                DateRoundingMode::None,
                new DateTimeImmutable('2026-04-10 20:24:25'),
                new DateTimeImmutable('2026-05-09 20:24:25')
            ],
            [
                '-2 days',
                [
                    DateInterval::createFromDateString('-2 days'),
                ],
                DateRoundingMode::None,
                new DateTimeImmutable('2026-04-10 20:24:25'),
                new DateTimeImmutable('2026-04-08 20:24:25')
            ],
            [
                '-2 days -2 hours',
                [
                    DateInterval::createFromDateString('-2 days'),
                    DateInterval::createFromDateString('-2 hours'),
                ],
                DateRoundingMode::None,
                new DateTimeImmutable('2026-04-10 20:24:25'),
                new DateTimeImmutable('2026-04-08 18:24:25')
            ],
            [
                '+1 day',
                [
                    DateInterval::createFromDateString('+1 day'),
                ],
                DateRoundingMode::ToStart,
                new DateTimeImmutable('2026-04-10 20:24:25'),
                new DateTimeImmutable('2026-04-11 00:00:00')
            ],
            [
                '+1 day',
                [
                    DateInterval::createFromDateString('+1 day'),
                ],
                DateRoundingMode::ToEnd,
                new DateTimeImmutable('2026-04-10 20:24:25'),
                new DateTimeImmutable('2026-04-11 23:59:59')
            ],
        ];
    }

    #[DataProvider('intervalsProvider')]
    public function testApplyToDate(
        string $intervalString,
        array $parsedIntervals,
        DateRoundingMode $dateRoundingMode,
        DateTimeImmutable $applyTo,
        DateTimeImmutable $expected,
    ): void {
        $result = new ParsedDateInterval($intervalString, $parsedIntervals, roundingMode: $dateRoundingMode);

        $this->assertEquals($expected, $result->applyToDate($applyTo));
    }
}
