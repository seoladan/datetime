<?php

declare(strict_types=1);

namespace Seoladan\DateTime\Tests;

use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\DateTime\DateRoundingMode;
use Seoladan\DateTime\DateRoundingUnit;
use Seoladan\DateTime\Exception\ParseDateException;
use Seoladan\DateTime\Exception\ParseDateIntervalException;
use Seoladan\DateTime\Exception\UnsupportedDateFormatException;
use Seoladan\DateTime\Now;
use Seoladan\DateTime\ParsedDateInterval;
use Seoladan\DateTime\Parser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Parser::class)]
#[UsesClass(DateRoundingMode::class)]
#[UsesClass(DateRoundingUnit::class)]
#[UsesClass(Now::class)]
#[UsesClass(ParsedDateInterval::class)]
#[UsesClass(ParseDateException::class)]
#[UsesClass(ParseDateIntervalException::class)]
class ParserTest extends TestCase
{
    public function testGetNow()
    {
        $now = new Now();
        $parser = new Parser($now);

        $this->assertEquals($now, $parser->getNow());
    }

    public static function parseDateProvider(): array
    {
        return [
            'A100' => [['2024-10-10', 'Y-m-d', null, null,], new DateTimeImmutable('2024-10-10 00:00:00'),],
            'A101' => [['2024-10-10', 'Y-m-d', null, null,], new DateTimeImmutable('2024-10-10 00:00:00'),],
            'A102' => [['2024-10-10', 'Y-m-d', null, null,], new DateTimeImmutable('2024-10-10 00:00:00'),],
            'A200' => [
                ['2024-10-10', 'Y-m-d', DateRoundingMode::None, null,],
                new DateTimeImmutable('2024-10-10 01:30:00'),
            ],
            'A201' => [
                ['2024-10-10', 'Y-m-d', DateRoundingMode::ToStart, null,],
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            'A202' => [
                ['2024-10-10', 'Y-m-d', DateRoundingMode::ToEnd, null,],
                new DateTimeImmutable('2024-10-10 23:59:59'),
            ],
            'A300' => [
                ['2024-10-10', 'Y-m-d', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            'A301' => [
                ['2024-10-10', 'Y-m-d', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            'A302' => [
                ['2024-10-10', 'Y-m-d', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            'A400' => [
                ['2024-10-10', 'Y-m-d', DateRoundingMode::None, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-10-10 01:30:00'),
            ],
            'A401' => [
                ['2024-10-10', 'Y-m-d', DateRoundingMode::ToStart, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-10-10 00:00:00'),
            ],
            'A402' => [
                ['2024-10-10', 'Y-m-d', DateRoundingMode::ToEnd, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-10-10 23:59:59'),
            ],
            'A110' => [['15/12/2024', 'd/m/Y', null, null,], new DateTimeImmutable('2024-12-15 00:00:00'),],
            'A111' => [['15/12/2024', 'd/m/Y', null, null,], new DateTimeImmutable('2024-12-15 00:00:00'),],
            'A112' => [['15/12/2024', 'd/m/Y', null, null,], new DateTimeImmutable('2024-12-15 00:00:00'),],
            'A210' => [
                ['15/12/2024', 'd/m/Y', DateRoundingMode::None, null,],
                new DateTimeImmutable('2024-12-15 01:30:00'),
            ],
            'A211' => [
                ['15/12/2024', 'd/m/Y', DateRoundingMode::ToStart, null,],
                new DateTimeImmutable('2024-12-15 00:00:00'),
            ],
            'A212' => [
                ['15/12/2024', 'd/m/Y', DateRoundingMode::ToEnd, null,],
                new DateTimeImmutable('2024-12-15 23:59:59'),
            ],
            'A310' => [
                ['15/12/2024', 'd/m/Y', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-12-15 00:00:00'),
            ],
            'A311' => [
                ['15/12/2024', 'd/m/Y', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-12-15 00:00:00'),
            ],
            'A312' => [
                ['15/12/2024', 'd/m/Y', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-12-15 00:00:00'),
            ],
            'A410' => [
                ['15/12/2024', 'd/m/Y', DateRoundingMode::None, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-12-15 01:30:00'),
            ],
            'A411' => [
                ['15/12/2024', 'd/m/Y', DateRoundingMode::ToStart, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-12-15 00:00:00'),
            ],
            'A412' => [
                ['15/12/2024', 'd/m/Y', DateRoundingMode::ToEnd, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-12-15 23:59:59'),
            ],
            'A120' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', null, null,],
                new DateTimeImmutable('2024-08-10 22:21:20'),
            ],
            'A121' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', null, null,],
                new DateTimeImmutable('2024-08-10 22:21:20'),
            ],
            'A122' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', null, null,],
                new DateTimeImmutable('2024-08-10 22:21:20'),
            ],
            'A220' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', DateRoundingMode::None, null,],
                new DateTimeImmutable('2024-08-10 22:21:20'),
            ],
            'A221' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', DateRoundingMode::ToStart, null,],
                new DateTimeImmutable('2024-08-10 22:21:20'),
            ],
            'A222' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', DateRoundingMode::ToEnd, null,],
                new DateTimeImmutable('2024-08-10 22:21:20'),
            ],
            'A320' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-08-10 00:00:00'),
            ],
            'A321' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-08-10 00:00:00'),
            ],
            'A322' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', null, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-08-10 00:00:00'),
            ],
            'A420' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', DateRoundingMode::None, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-08-10 22:21:20'),
            ],
            'A421' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', DateRoundingMode::ToStart, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-08-10 00:00:00'),
            ],
            'A422' => [
                ['22:21:20 10/08/2024', 'H:i:s d/m/Y', DateRoundingMode::ToEnd, DateRoundingUnit::Day,],
                new DateTimeImmutable('2024-08-10 23:59:59'),
            ],
        ];
    }

    #[DataProvider('parseDateProvider')]
    public function testParseDate(
        array $params,
        DateTimeImmutable $expected,
    ): void {
        $parser = new Parser(new Now(new DateTimeImmutable('2024-10-11 01:30:00')));

        $this->assertEquals($expected, $parser->parseDate(...$params));
    }

    public static function parseDateThrowsProvider(): array
    {
        return [
            [
                '',
                '',
                UnsupportedDateFormatException::class,
            ],
            [
                '20-12-2420',
                'd/m/Y',
                ParseDateException::class,
            ],
        ];
    }

    /**
     * @param string $dateString
     * @param string $dateFormat
     * @param class-string<Exception> $expectedException
     * @return void
     */
    #[DataProvider('parseDateThrowsProvider')]
    public function testParseDateThrows(
        string $dateString,
        ?string $dateFormat,
        ?string $expectedException,
    ) {
        $parser = new Parser();

        $this->expectException($expectedException);

        $parser->parseDate($dateString, $dateFormat);
    }
}
