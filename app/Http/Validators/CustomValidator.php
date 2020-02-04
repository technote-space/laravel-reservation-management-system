<?php
declare(strict_types=1);

namespace App\Http\Validators;

use App\Models\Reservation;
use Illuminate\Support\Arr;
use Illuminate\Validation\Validator;

/**
 * Class ExtensionValidator
 * @package App\Http\Validators
 * @SuppressWarnings(PHPMD.UndefinedVariable)
 */
class CustomValidator extends Validator
{
    /** @var array $reservation */
    private $reservation = [];

    /**
     * @return array
     */
    private function getReservation()
    {
        $reservationId = request()->route('reservation');
        if (! array_key_exists($reservationId, $this->reservation)) {
            $this->reservation[$reservationId] = $reservationId ? Reservation::findOrFail($reservationId) : null;
        }

        $reservation = $this->reservation[$reservationId];
        if ($reservation) {
            $reservationId -= 0;
        } else {
            $reservationId = null;
        }

        return [$reservation, $reservationId];
    }

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
        /** @var Reservation $reservation */
        [$reservation] = $this->getReservation();
        if ($reservation) {
            $data         = $this->getData();
            $startDate    = Arr::get($data, 'reservations.start_date', $reservation->start_date_str);
            $endDate      = Arr::get($data, 'reservations.end_date', $reservation->end_date_str);
            $checkoutTime = Arr::get($data, 'reservations.checkout', $reservation->checkout);
        } else {
            $startDate    = $this->getValue('reservations.start_date');
            $endDate      = $this->getValue('reservations.end_date');
            $checkoutTime = $this->getValue('reservations.checkout');
        }

        return Reservation::isTermValid($startDate, $endDate, $checkoutTime);
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
        [$reservation, $reservationId] = $this->getReservation();
        if ($reservation) {
            $data      = $this->getData();
            $roomId    = Arr::get($data, 'reservations.room_id', $reservation->room_id);
            $startDate = Arr::get($data, 'reservations.start_date', $reservation->start_date_str);
            $endDate   = Arr::get($data, 'reservations.end_date', $reservation->end_date_str);
        } else {
            $roomId    = $this->getValue('reservations.room_id');
            $startDate = $this->getValue('reservations.start_date');
            $endDate   = $this->getValue('reservations.end_date');
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
        [$reservation, $reservationId] = $this->getReservation();
        if ($reservation) {
            $data      = $this->getData();
            $guestId   = Arr::get($data, 'reservations.guest_id', $reservation->guest_id);
            $startDate = Arr::get($data, 'reservations.start_date', $reservation->start_date_str);
            $endDate   = Arr::get($data, 'reservations.end_date', $reservation->end_date_str);
        } else {
            $guestId   = $this->getValue('reservations.guest_id');
            $startDate = $this->getValue('reservations.start_date');
            $endDate   = $this->getValue('reservations.end_date');
        }

        return Reservation::isNotDuplicated($reservationId, $guestId, $startDate, $endDate);
    }
}
