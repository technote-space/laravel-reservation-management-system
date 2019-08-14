<?php
declare(strict_types=1);

namespace App\Repositories\Crud;

use App\Models\Reservation;
use App\Repositories\Contracts\CrudRepository as CrudRepositoryContract;
use App\Repositories\Traits\CrudRepository;

/**
 * Class ReservationRepository
 * @package App\Repositories\Crud
 */
class ReservationRepository implements CrudRepositoryContract
{
    use CrudRepository;

    /**
     * @return string
     */
    protected function getModel(): string
    {
        return Reservation::class;
    }

    /**
     * @return array
     */
    protected function getListEagerLoading(): array
    {
        return [
            'guest',
            'room',
        ];
    }

    /**
     * @return array
     */
    protected function getDetailEagerLoading(): array
    {
        return [
            'guest',
            'room',
        ];
    }
}
