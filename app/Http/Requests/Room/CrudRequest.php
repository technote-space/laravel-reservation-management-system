<?php
declare(strict_types=1);

namespace App\Http\Requests\Room;

use App\Models\Room;
use Doctrine\DBAL\Schema\Column;
use Eloquent;

/**
 * Class UpdateRequest
 * @package App\Http\Requests\Room
 */
abstract class CrudRequest extends \App\Http\Requests\CrudRequest
{
    /**
     * @return string|Eloquent
     */
    protected function getTarget()
    {
        return Room::class;
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
        if ('rooms.number' === $name) {
            $rules[] = 'min:1';
        }

        return $rules;
    }
}
