<?php
declare(strict_types=1);

namespace App\Repositories\Crud;

use App\Models\Room;
use App\Repositories\Contracts\CrudRepository as CrudRepositoryContract;
use App\Repositories\Traits\CrudRepository;

/**
 * Class RoomRepository
 * @package App\Repositories\Crud
 */
class RoomRepository implements CrudRepositoryContract
{
    use CrudRepository;

    /**
     * @return string
     */
    protected function getModel(): string
    {
        return Room::class;
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
