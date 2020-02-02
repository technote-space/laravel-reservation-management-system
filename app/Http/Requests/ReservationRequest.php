<?php
declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

/**
 * Class ReservationRequest
 * @package App\Http\Requests
 */
class ReservationRequest extends FormRequest
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
            'id'      => 'required|filled|integer|exists:reservations,id',
            'payment' => 'nullable|integer',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'id'      => __('request.reservation.id'),
            'payment' => __('request.reservation.payment'),
        ];
    }

    /**
     * @return Reservation|Collection|Model
     */
    public function getReservation()
    {
        return Reservation::findOrFail(Arr::get($this->validated(), 'id'));
    }

    /**
     * @return int|null
     */
    public function getPayment()
    {
        $payment = Arr::get($this->validated(), 'payment');
        if ('' === (string) $payment) {
            return null;
        }

        return (int) $payment;
    }
}
