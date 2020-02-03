<?php
declare(strict_types=1);

namespace App\Http\Services;

use App\Helpers\Traits\TimeHelper;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ReservationService
 * @package App\Http\Services
 */
class ReservationService
{
    use TimeHelper;

    /**
     * @param  Carbon  $fromDate
     * @param  Carbon  $toDate
     *
     * @return Collection
     */
    public function getCheckinList(Carbon $fromDate, Carbon $toDate): Collection
    {
        $checkin = static::getCheckinTime();
        $concat  = "CONCAT(start_date, ' ', '$checkin')";
        if ('sqlite' === config('database.default')) {
            $concat = "start_date || ' ' || '$checkin'";
        }

        return Reservation::whereBetween(DB::raw($concat), [$fromDate, $toDate])
                          ->where('status', '!=', 'canceled')
                          ->orderBy(DB::raw($concat))
                          ->get();
    }

    /**
     * @param  Carbon  $date
     *
     * @return Collection
     */
    public function getDateCheckinList(Carbon $date): Collection
    {
        return $this->getCheckinList($date->copy()->startOfDay(), $date->copy()->endOfDay());
    }

    /**
     * @param  Carbon  $fromDate
     * @param  Carbon  $toDate
     *
     * @return Collection
     */
    public function getCheckoutList(Carbon $fromDate, Carbon $toDate): Collection
    {
        $concat = 'CONCAT(end_date, " ", checkout)';
        if ('sqlite' === config('database.default')) {
            $concat = 'end_date || " " || checkout';
        }

        return Reservation::whereBetween(DB::raw($concat), [$fromDate->subDay(), $toDate->subDay()])
                          ->where('status', '!=', 'canceled')
                          ->orderBy(DB::raw($concat))
                          ->get();
    }

    /**
     * @param  Carbon  $date
     *
     * @return Collection
     */
    public function getDateCheckoutList(Carbon $date): Collection
    {
        return $this->getCheckoutList($date->copy()->startOfDay(), $date->copy()->endOfDay());
    }
}
