<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function checkout()
    {
        return $this->service->getTodayCheckoutList();
    }
}
