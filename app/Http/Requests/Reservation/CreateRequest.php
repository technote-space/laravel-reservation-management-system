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
     * @return bool
     */
    protected function isUpdate(): bool
    {
        return false;
    }
}
