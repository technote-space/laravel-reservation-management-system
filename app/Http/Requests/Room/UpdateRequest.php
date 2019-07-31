<?php
declare(strict_types=1);

namespace App\Http\Requests\Room;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Room
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
