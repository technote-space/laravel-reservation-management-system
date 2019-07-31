<?php
declare(strict_types=1);

namespace App\Http\Requests\Guest;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Guest
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
