<?php
declare(strict_types=1);

namespace App\Http\Requests\Guest;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Guest
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
