<?php
declare(strict_types=1);

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use Throwable;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Reservation
 */
class UpdateRequest extends CrudRequest
{
    /**
     * @return int
     * @throws Throwable
     */
    protected function getRoomId()
    {
        return Reservation::findOrFail($this->route($this->getSingularName()))->room_id;
    }
}
