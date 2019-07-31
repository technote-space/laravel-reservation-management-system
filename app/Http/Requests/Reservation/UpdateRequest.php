<?php
declare(strict_types=1);

namespace App\Http\Requests\Reservation;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Reservation
 */
class UpdateRequest extends CrudRequest
{
    /**
     * @return bool
     */
    protected function isUpdate(): bool
    {
        return true;
    }
}
