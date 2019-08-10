<?php
declare(strict_types=1);

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

/**
 * Class ReservationCheckRequest
 * @package App\Http\Requests\Reservation
 */
class ReservationCheckRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'reservation_id' => 'nullable|integer|exists:reservations,id',
            'room_id'        => 'nullable|integer|exists:rooms,id',
            'guest_id'       => 'nullable|integer|exists:guests,id',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'reservation_id' => __('database.primary_id'),
            'room_id'        => __('database.reservations.room_id'),
            'guest_id'       => __('database.reservations.guest_id'),
            'start_date'     => __('database.reservations.start_date'),
            'end_date'       => __('database.reservations.end_date'),
        ];
    }

    /**
     * @return array
     */
    public function checkReservable()
    {
        $data = $this->validated();

        if (! Reservation::isTermValid(Arr::get($data, 'start_date'), Arr::get($data, 'end_date'))) {
            return [
                'result'  => false,
                'message' => __('validation.reservation_term'),
            ];
        }

        if (! Reservation::isReservationAvailable(Arr::get($data, 'reservation_id'), Arr::get($data, 'room_id'), Arr::get($data, 'start_date'), Arr::get($data, 'end_date'))) {
            return [
                'result'  => false,
                'message' => __('validation.reservation_availability'),
            ];
        }

        if (! Reservation::isNotDuplicated(Arr::get($data, 'reservation_id'), Arr::get($data, 'guest_id'), Arr::get($data, 'start_date'), Arr::get($data, 'end_date'))) {
            return [
                'result'  => false,
                'message' => __('validation.reservation_duplicate'),
            ];
        }

        return [
            'result' => true,
        ];
    }
}
