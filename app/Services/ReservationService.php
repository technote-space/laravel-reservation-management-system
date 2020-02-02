<?php
declare(strict_types=1);

namespace App\Http\Services;

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
        return Reservation::whereBetween(DB::raw($concat), [$fromDate, $toDate])
                          ->orderBy(DB::raw($concat))
                          ->get();
    }

    /**
     * @return Collection
     */
    public function getTodayCheckoutList(): Collection
    {
        return $this->getCheckoutList(Carbon::today()->startOfDay(), Carbon::today()->endOfDay());
    }
}
