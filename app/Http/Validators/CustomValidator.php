<?php
declare(strict_types=1);

namespace App\Http\Validators;

use App\Models\Reservation;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

/**
 * Class ExtensionValidator
 * @package App\Http\Validators
 */
class CustomValidator extends Validator
{
    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     *
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validateReservationTerm(/** @noinspection PhpUnusedParameterInspection */ $attribute, $value, $parameters)
    {
        $reservationId = request()->route('reservation');
        $reservation   = $reservationId ? Reservation::find($reservationId) : null;
        if ($reservation) {
            $data      = $this->getData();
            $startDate = Arr::get($data, 'reservations.start_date', $reservation->start_date_str);
            $endDate   = Arr::get($data, 'reservations.end_date', $reservation->end_date_str);
        } else {
            $startDate = $this->getValue('reservations.start_date');
            $endDate   = $this->getValue('reservations.end_date');
        }

        return Reservation::isTermValid($startDate, $endDate);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     *
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validateReservationAvailability(/** @noinspection PhpUnusedParameterInspection */ $attribute, $value, $parameters)
    {
        $reservationId = request()->route('reservation');
        $reservation   = $reservationId ? Reservation::find($reservationId) : null;
        if ($reservation) {
            $reservationId -= 0;
            $data          = $this->getData();
            $roomId        = Arr::get($data, 'reservations.room_id', $reservation->room_id);
            $startDate     = Arr::get($data, 'reservations.start_date', $reservation->start_date_str);
            $endDate       = Arr::get($data, 'reservations.end_date', $reservation->end_date_str);
        } else {
            $reservationId = null;
            $roomId        = $this->getValue('reservations.room_id');
            $startDate     = $this->getValue('reservations.start_date');
            $endDate       = $this->getValue('reservations.end_date');
        }

        return Reservation::isReservationAvailable($reservationId, $roomId, $startDate, $endDate);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     *
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validateReservationDuplicate(/** @noinspection PhpUnusedParameterInspection */ $attribute, $value, $parameters)
    {
        $reservationId = request()->route('reservation');
        $reservation   = $reservationId ? Reservation::find($reservationId) : null;
        if ($reservation) {
            $reservationId -= 0;
            $data          = $this->getData();
            $guestId       = Arr::get($data, 'reservations.guest_id', $reservation->guest_id);
            $startDate     = Arr::get($data, 'reservations.start_date', $reservation->start_date_str);
            $endDate       = Arr::get($data, 'reservations.end_date', $reservation->end_date_str);
        } else {
            $reservationId = null;
            $guestId       = $this->getValue('reservations.guest_id');
            $startDate     = $this->getValue('reservations.start_date');
            $endDate       = $this->getValue('reservations.end_date');
        }

        return Reservation::isNotDuplicated($reservationId, $guestId, $startDate, $endDate);
    }
}
