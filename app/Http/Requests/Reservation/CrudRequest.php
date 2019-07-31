<?php
declare(strict_types=1);

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use Doctrine\DBAL\Schema\Column;
use Eloquent;

/**
 * Class CrudRequest
 * @package App\Http\Requests\Reservation
 */
abstract class CrudRequest extends \App\Http\Requests\CrudRequest
{
    /**
     * @return string|Eloquent
     */
    protected function getTarget()
    {
        return Reservation::class;
    }

    /**
     * @param  array  $rules
     * @param  string  $name
     * @param  Column  $column
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function filterRules(/** @noinspection PhpUnusedParameterInspection */ array $rules, string $name, Column $column): array
    {
        if ('reservations.end_date' === $name) {
            $rules[] = 'after_or_equal:start_date';
        }
        if ('reservations.number' === $name) {
            $rules[] = 'min:1';
        }

        return $rules;
    }
}
