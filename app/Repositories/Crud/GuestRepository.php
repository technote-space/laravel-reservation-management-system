<?php
declare(strict_types=1);

namespace App\Repositories\Crud;

use App\Models\Guest;
use App\Repositories\Contracts\CrudRepository as CrudRepositoryContract;
use App\Repositories\Traits\CrudRepository;

/**
 * Class GuestRepository
 * @package App\Repositories\Crud
 */
class GuestRepository implements CrudRepositoryContract
{
    use CrudRepository;

    /**
     * @return string
     */
    protected function getModel(): string
    {
        return Guest::class;
    }

    /**
     * @return array
     */
    protected function getListEagerLoading(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getDetailEagerLoading(): array
    {
        return [
            'latestReservation',
            'latestUsage',
            'recentUsages',
        ];
    }
}
