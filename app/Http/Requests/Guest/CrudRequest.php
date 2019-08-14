<?php
declare(strict_types=1);

namespace App\Http\Requests\Guest;

use App\Models\Guest;
use App\Models\GuestDetail;
use Eloquent;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Guest
 */
class CrudRequest extends \App\Http\Requests\CrudRequest
{
    /**
     * @return string|Eloquent
     */
    protected function getTarget()
    {
        return Guest::class;
    }

    /**
     * @return array
     */
    protected function getSubTargets(): array
    {
        return [
            'detail' => GuestDetail::class,
        ];
    }
}
