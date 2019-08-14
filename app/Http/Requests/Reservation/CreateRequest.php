<?php
declare(strict_types=1);

namespace App\Http\Requests\Reservation;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Reservation
 */
class CreateRequest extends CrudRequest
{
    /**
     * @return array|int|string|null
     */
    protected function getRoomId()
    {
        return $this->input('reservations.room_id');
    }
}
