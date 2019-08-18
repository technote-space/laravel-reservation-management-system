<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\ReservationCheckRequest;

/**
 * Class ReservationController
 * @package App\Http\Controllers\Api
 */
class ReservationController extends Controller
{
    /**
     * @param  ReservationCheckRequest  $request
     *
     * @return array
     */
    public function check(ReservationCheckRequest $request)
    {
        return $request->checkReservable();
    }
}
