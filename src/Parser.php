<?php

declare(strict_types=1);

namespace Seoladan\DateTime;

use DateInterval;
use DateTimeImmutable;
use Seoladan\DateTime\Exception\ParseDateException;
use Seoladan\DateTime\Exception\ParseDateIntervalException;

readonly class Parser
{
    public function __construct(
        private Now $now = new Now()
    ) {
    }

    /**
     * @return Now
     * @internal
     */
    public function getNow(): Now
    {
        return $this->now;
    }

    /**
     * @param string $date
     * @param string $usingFormat
     * @param ?DateRoundingMode $roundingMode
     * @param ?DateRoundingUnit $roundingUnit
     * @return DateTimeImmutable
     * @throws ParseDateException
     */
    public function parseDate(
        string $date,
        string $usingFormat,
        ?DateRoundingMode $roundingMode = null,
        ?DateRoundingUnit $roundingUnit = null,
    ): DateTimeImmutable {
        $roundingMode = $roundingMode ?? DateRoundingMode::ToStart;
        $formatRoundingUnit = DateRoundingUnit::fromDateFormat($usingFormat);

        $parsedDate = DateTimeImmutable::createFromFormat($usingFormat, $date);

        if ($parsedDate === false) {
            throw new ParseDateException($date, $usingFormat);
        }

        return match ($roundingMode) {
            default => $roundingMode->round($parsedDate, $roundingUnit ?? $formatRoundingUnit),
            /**
             * @internal For `$date` strings that do not include a time element, the time portion of the `$parsedDate`
             *           returned by {@see DateTimeImmutable::createFromFormat()} will be that of the current system
             *           time. So that the functionality provided by {@link self::parseDate()} can be tested,
             *           {@link self::setNowDateTime()} allows "now" to be fixed to a defined value - to accommodate
             *           this when `$roundingMode` is {@link DateRoundingMode::None}, we need to reset the time portion
             *           of `$parsedDate` to that provided by {@link self::setNowDateTime()}. This adjustment
             *           effectively does nothing when "now" has not been overridden
             */
            DateRoundingMode::None => $formatRoundingUnit->roundToStart($parsedDate)->add(
                $formatRoundingUnit->roundToStart($this->now->getDateTime())->diff($this->now->getDateTime())
            ),
        };
    }

    /**
     * @param string $interval
     * @param ?string $relativeDateFormat
     * @param ?DateRoundingMode $roundingMode
     * @param ?DateRoundingUnit $roundingUnit
     * @return ParsedDateInterval|false Returns `false` if `$interval` doesn't contains at least one date interval string
     */
    public function parseDateInterval(
        string $interval,
        ?string $relativeDateFormat,
        ?DateRoundingMode $roundingMode = null,
        ?DateRoundingUnit $roundingUnit = null,
    ): ParsedDateInterval|false {
        if (preg_match_all(
                '/(?<intervals>(?:[+-] ?)?[0-9]+ ?(?<types>year|month|week|day|hour|min(?:ute)?)s?)/',
                $interval,
                $timeframes,
            ) === 0) {
            return false;
        }

        // Prevent intervals such as "2 days -1 day"
        $timeframes['types'] = str_replace('minute', 'min', $timeframes['types']);

        if (max(array_count_values($timeframes['types'])) > 1) {
            throw new ParseDateIntervalException($interval);
        }

        $timeframes['intervals'] = str_replace(['- ', '+ '], ['-', '+'], $timeframes['intervals']);

        $relativeTo = null;
        $relativeToRoundingMode = DateRoundingMode::None;

        foreach ($this->getSupportedIntervalPatterns() as $pattern => $mapper) {
            if (!preg_match($pattern, $interval, $matches)) {
                continue;
            }

            if (isset($matches['relativeTo'])) {
                $roundingMode = $roundingMode ?? DateRoundingMode::ToStart;

                $relativeTo = match ($matches['relativeTo']) {
                    'now', 'today' => null,
                    default => $this->parseIntervalDate(
                        $matches['relativeTo'],
                        $relativeDateFormat,
                        $interval,
                        $roundingMode,
                        $roundingUnit,
                    )
                };

                $relativeToRoundingMode = match($matches['relativeTo']) {
                    'today' => $roundingMode,
                    default => DateRoundingMode::None,
                };
            }

            $intervals = array_filter(
                array_map(
                    static fn(string $timeframe) => DateInterval::createFromDateString($timeframe),
                    array_map($mapper, $timeframes['intervals'])
                )
            );

            if (count($intervals) === count($timeframes['intervals'])) {
                return new ParsedDateInterval($interval, $intervals, $relativeTo, $relativeToRoundingMode);
            }
        }

        throw new ParseDateIntervalException($interval);
    }

    /**
     * @return array
     */
    public function getSupportedIntervalPatterns(): array
    {
        return [
            /**
             * Match a relative date that must be in the past, e.g. "2 days ago", "1 month before 25/12/2026" etc.
             *
             * @lang RegExp
             */
            '/^(?<timeframe>(?<interval>[0-9]+ ?(?:year|month|week|day|hour|min(?:ute)?)s?)(?: (?&interval))*) (?:ago|before (?<relativeTo>.+))$/' =>
                static fn(string $timeframe): string => '-' . $timeframe,
            /**
             * Match a relative date that must be in the future, e.g. "2 days", "1 month from 25/12/2026" etc.
             *
             * @lang RegExp
             */
            '/^(?<timeframe>(?<interval>[0-9]+ ?(?:year|month|week|day|hour|min(?:ute)?)s?)(?: (?&interval))*) (?:from|after) (?<relativeTo>.+)$/' =>
                static fn(string $timeframe): string => $timeframe,
            /**
             * Match a relative date that could be in the past or the future, e.g. "+2 days", "-1 week" etc.
             *
             * @lang RegExp
             */
            '/^(?<timeframe>(?<interval>(?:[+-] ?)?[0-9]+ ?(?:year|month|week|day|hour|min(?:ute)?)s?)(?: (?&interval))*)$/' =>
                static fn(string $timeframe): string => $timeframe,
        ];
    }

    /**
     * @param string $date
     * @param string|null $dateFormat
     * @param string $fromInterval
     * @param DateRoundingMode|null $roundingMode
     * @param DateRoundingUnit|null $roundingUnit
     * @return DateTimeImmutable
     */
    private function parseIntervalDate(
        string $date,
        ?string $dateFormat,
        string $fromInterval,
        ?DateRoundingMode $roundingMode,
        ?DateRoundingUnit $roundingUnit
    ): DateTimeImmutable {
        if ($dateFormat === null) {
            throw new ParseDateIntervalException($fromInterval);
        }

        $roundingUnit = $roundingUnit ?? DateRoundingUnit::fromDateFormat($dateFormat);

        try {
            return $this->parseDate($date, $dateFormat, $roundingMode, $roundingUnit);
        } catch (ParseDateException $e) {
            throw new ParseDateIntervalException($fromInterval, $e);
        }
    }
}
