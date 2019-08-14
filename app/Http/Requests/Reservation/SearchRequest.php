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
            'room_id'    => 'filled|integer|exists:rooms,id',
        ];
    }

    protected function additionalAttributes(): array
    {
        return [
            'start_date' => __('request.reservations.start_date'),
            'end_date'   => __('request.reservations.end_date'),
            'room_id'    => __('request.reservations.room_id'),
        ];
    }
}
