<?php
declare(strict_types=1);

namespace App\Http\Requests\Room;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Room
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
