<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Http\Services\ReservationService;
use Illuminate\Support\Collection;

/**
 * Class ReservationController
 * @package App\Http\Controllers\Api
 */
class ReservationController extends Controller
{
    /** @var ReservationService $service */
    private $service;

    public function __construct()
    {
        $this->service = new ReservationService();
    }

    /**
     * @return Collection
     */
    public function checkoutList()
    {
        return $this->service->getTodayCheckoutList();
    }

    /**
     * @param  ReservationRequest  $request
     *
     * @return array
     */
    public function checkin(ReservationRequest $request)
    {
        return ['result' => $request->getReservation()->checkin()];
    }

    /**
     * @param  ReservationRequest  $request
     *
     * @return array
     */
    public function checkout(ReservationRequest $request)
    {
        return ['result' => $request->getReservation()->checkout($request->getPayment())];
    }

    /**
     * @param  ReservationRequest  $request
     *
     * @return array
     */
    public function cancel(ReservationRequest $request)
    {
        return ['result' => $request->getReservation()->cancel()];
    }
}
