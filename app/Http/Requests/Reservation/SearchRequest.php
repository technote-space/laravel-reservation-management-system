<?php
declare(strict_types=1);

namespace App\Http\Requests\Reservation;

/**
 * Class CreateRequest
 * @package App\Http\Requests\Reservation
 */
class SearchRequest extends \App\Http\Requests\SearchRequest
{
    protected function additionalRules(): array
    {
        return [
            'start_date' => 'filled|date',
            'end_date'   => 'filled|date',
        ];
    }

    protected function additionalAttributes(): array
    {
        return [
            'start_date' => __('request.reservations.start_date'),
            'end_date'   => __('request.reservations.end_date'),
        ];
    }
}
