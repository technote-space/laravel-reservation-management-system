<?php
declare(strict_types=1);

namespace App\Http\Services;

use App\Models\Reservation;
use ArrayAccess;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Class SummaryService
 * @package App\Http\Services
 */
class SummaryService
{
    /**
     * @param  array  $conditions
     *
     * @return array
     */
    public function getSummary(array $conditions)
    {
        $summary = $this->initSummary($conditions);
        $builder = Reservation::query()
                              ->with('detail')
                              ->whereDate('start_date', '>=', $conditions['start_date'])
                              ->whereDate('start_date', '<', $conditions['end_date']);

        if (! empty($conditions['room_id'])) {
            $builder->where('room_id', $conditions['room_id']);
        }

        $builder->chunk(1000, function (Collection $records) use ($conditions, &$summary) {
            $records->each(function (Reservation $record) use ($conditions, &$summary) {
                $summary[$this->getKey($conditions, $record)] += $record->detail->payment ?? 0;
            });
        });

        return $summary;
    }

    /**
     * @param  array  $conditions
     *
     * @return array
     */
    private function initSummary(array $conditions)
    {
        return collect($this->createRange($conditions))->mapWithKeys(function ($date) use ($conditions) {
            /** @var Carbon $date */
            return [$date->format($this->getFormat($conditions)) => 0];
        })->toArray();
    }

    /**
     * @param  array  $conditions
     *
     * @return array
     */
    private function createRange(array $conditions)
    {
        if ('daily' === $conditions['type']) {
            return CarbonPeriod::create($conditions['start_date'], Carbon::parse($conditions['end_date'])->subDay()->format('Y-m-d'))->toArray();
        }

        return CarbonPeriod::create($conditions['start_date'], Carbon::parse($conditions['end_date'])->subDay()->format('Y-m-d'))->months()->toArray();
    }

    /**
     * @param  array  $conditions
     * @param  Reservation  $record
     *
     * @return string
     */
    private function getKey(array $conditions, Reservation $record)
    {
        return $record->start_date->format($this->getFormat($conditions));
    }

    /**
     * @param  array  $conditions
     *
     * @return int
     */
    private function getFormat(array $conditions)
    {
        if ('daily' === $conditions['type']) {
            return 'Y-m-d';
        }

        return 'Y-m';
    }
}
