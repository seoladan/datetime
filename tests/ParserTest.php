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

    public static function parseDateIntervalProvider(): array
    {
        return [
            'D100' => [
                ['2 days', null, null,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D101' => [
                ['2 days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D102' => [
                ['2 days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D103' => [
                ['2 days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D104' => [
                ['2days', null, null,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D105' => [
                ['2days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D106' => [
                ['2days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D107' => [
                ['2days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D108' => [
                ['+2 days', null, null,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),],
            'D109' => [
                ['+2 days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D110' => [
                ['+2 days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D111' => [
                ['+2 days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D112' => [
                ['-2 days', null, null,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D113' => [
                ['-2 days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D114' => [
                ['-2 days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D115' => [
                ['-2 days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D116' => [
                ['2 days after now', null, null,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D117' => [
                ['2 days after now', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D118' => [
                ['2 days after now', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D119' => [
                ['2 days after now', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D120' => [
                ['2 days before now', null, null,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D121' => [
                ['2 days before now', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D122' => [
                ['2 days before now', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D123' => [
                ['2 days before now', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D124' => [
                ['2 days after today', null, null,],
                null,
                new DateTimeImmutable('2024-10-13 00:00:00'),
            ],
            'D125' => [
                ['2 days after today', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-10-13 01:30:00'),
            ],
            'D126' => [
                ['2 days after today', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-10-13 00:00:00'),
            ],
            'D127' => [
                ['2 days after today', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-10-13 23:59:59'),
            ],
            'D128' => [
                ['2 days before today', null, null,],
                null,
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            'D129' => [
                ['2 days before today', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-10-09 01:30:00'),
            ],
            'D130' => [
                ['2 days before today', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-10-09 00:00:00'),
            ],
            'D131' => [
                ['2 days before today', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-10-09 23:59:59'),
            ],
            'D132' => [
                ['1 month 2 days', null, null,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D133' => [
                ['1 month 2 days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D134' => [
                ['1 month 2 days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D135' => [
                ['1 month 2 days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D136' => [
                ['1 month + 2 days', null, null,],
                null, new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D137' => [
                ['1 month + 2 days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D138' => [
                ['1 month + 2 days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D139' => [
                ['1 month + 2 days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D140' => [
                ['+1 month +2 days', null, null,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D141' => [
                ['+1 month +2 days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D142' => [
                ['+1 month +2 days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D143' => [
                ['+1 month +2 days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D144' => [
                ['1 month - 2 days', null, null,],
                null,
                new DateTimeImmutable('2024-11-09 01:30:00'),
            ],
            'D145' => [
                ['1 month - 2 days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-11-09 01:30:00'),
            ],
            'D146' => [
                ['1 month - 2 days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-11-09 01:30:00'),
            ],
            'D147' => [
                ['1 month - 2 days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-11-09 01:30:00'),
            ],
            'D148' => [
                ['+1 month -2 days', null, null,],
                null,
                new DateTimeImmutable('2024-11-09 01:30:00'),
            ],
            'D149' => [
                ['+1 month -2 days', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-11-09 01:30:00'),
            ],
            'D150' => [
                ['+1 month -2 days', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-11-09 01:30:00'),
            ],
            'D151' => [
                ['+1 month -2 days', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-11-09 01:30:00'),
            ],
            'D152' => [
                ['1 month 2 days after now', null, null,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D153' => [
                ['1 month 2 days after now', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D154' => [
                ['1 month 2 days after now', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D155' => [
                ['1 month 2 days after now', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D156' => [
                ['1 month 2 days before now', null, null,],
                null,
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
            'D157' => [
                ['1 month 2 days before now', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
            'D158' => [
                ['1 month 2 days before now', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
            'D159' => [
                ['1 month 2 days before now', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
            'D160' => [
                ['1 month 2 days after today', null, null,],
                null,
                new DateTimeImmutable('2024-11-13 00:00:00'),
            ],
            'D161' => [
                ['1 month 2 days after today', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D162' => [
                ['1 month 2 days after today', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-11-13 00:00:00'),
            ],
            'D163' => [
                ['1 month 2 days after today', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-11-13 23:59:59'),
            ],
            'D164' => [
                ['1 month 2 days before today', null, null,],
                null,
                new DateTimeImmutable('2024-09-09 00:00:00'),
            ],
            'D165' => [
                ['1 month 2 days before today', null, DateRoundingMode::None,],
                null,
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
            'D166' => [
                ['1 month 2 days before today', null, DateRoundingMode::ToStart,],
                null,
                new DateTimeImmutable('2024-09-09 00:00:00'),
            ],
            'D167' => [
                ['1 month 2 days before today', null, DateRoundingMode::ToEnd,],
                null,
                new DateTimeImmutable('2024-09-09 23:59:59'),
            ],
            'D168' => [
                ['1 month 2 days after 01/02/2024', 'd/m/Y', null,],
                new DateTimeImmutable('2024-02-01 00:00:00'),
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D169' => [
                ['1 month 2 days after 01/02/2024', 'd/m/Y', DateRoundingMode::None,],
                new DateTimeImmutable('2024-02-01 01:30:00'),
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D170' => [
                ['1 month 2 days after 01/02/2024', 'd/m/Y', DateRoundingMode::ToStart,],
                new DateTimeImmutable('2024-02-01 00:00:00'),
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D171' => [
                ['1 month 2 days after 01/02/2024', 'd/m/Y', DateRoundingMode::ToEnd,],
                new DateTimeImmutable('2024-02-01 23:59:59'),
                new DateTimeImmutable('2024-11-13 01:30:00'),
            ],
            'D172' => [
                ['1 month 2 days before 01/02/2024', 'd/m/Y', null,],
                new DateTimeImmutable('2024-02-01 00:00:00'),
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
            'D173' => [
                ['1 month 2 days before 01/02/2024', 'd/m/Y', DateRoundingMode::None,],
                new DateTimeImmutable('2024-02-01 01:30:00'),
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
            'D174' => [
                ['1 month 2 days before 01/02/2024', 'd/m/Y', DateRoundingMode::ToStart,],
                new DateTimeImmutable('2024-02-01 00:00:00'),
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
            'D175' => [
                ['1 month 2 days before 01/02/2024', 'd/m/Y', DateRoundingMode::ToEnd,],
                new DateTimeImmutable('2024-02-01 23:59:59'),
                new DateTimeImmutable('2024-09-09 01:30:00'),
            ],
        ];
    }

    #[DataProvider('parseDateIntervalProvider')]
    public function testParseIntervalDate(
        array $params,
        ?DateTimeImmutable $expectedRelativeTo,
        DateTimeImmutable $expectedApplyToNow,
    ): void {
        $now = new DateTimeImmutable('2024-10-11 01:30:00');
        $parser = new Parser(new Now($now));

        $result = $parser->parseDateInterval(...$params);

        $this->assertEquals($expectedRelativeTo, $result->getRelativeTo());
        $this->assertEquals($expectedApplyToNow, $result->applyToDate($now));
    }

    public static function parseDateIntervalNoIntervalProvider(): array
    {
        return [
            [
                '',
                null,
            ],
            [
                '+1 quarter',
                'd/m/Y',
            ],
        ];
    }

    /**
     * @param string $intervalString
     * @param string $dateFormat
     * @param class-string<Exception> $expectedException
     * @return void
     */
    #[DataProvider('parseDateIntervalNoIntervalProvider')]
    public function testParseDateIntervalNoInterval(
        string $intervalString,
        ?string $dateFormat,
    ) {
        $parser = new Parser();

        $this->assertFalse($parser->parseDateInterval($intervalString, $dateFormat));
    }

    public static function parseDateIntervalThrowsProvider(): array
    {
        return [
            [
                '1 day -1 day',
                null,
                ParseDateIntervalException::class,
            ],
            [
                '1 day prior to next year',
                null,
                ParseDateIntervalException::class,
            ],
            [
                '1 day after 20/05/2025',
                null,
                ParseDateIntervalException::class,
            ],
            [
                '1 day after 20-12-2420',
                'd/m/Y',
                ParseDateIntervalException::class,
            ],
        ];
    }

    /**
     * @param string $intervalString
     * @param string $dateFormat
     * @param class-string<Exception> $expectedException
     * @return void
     */
    #[DataProvider('parseDateIntervalThrowsProvider')]
    public function testParseDateIntervalThrows(
        string $intervalString,
        ?string $dateFormat,
        string $expectedException,
    ) {
        $parser = new Parser();

        $this->expectException($expectedException);

        $parser->parseDateInterval($intervalString, $dateFormat);
    }
}
