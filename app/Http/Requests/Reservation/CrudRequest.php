<?php
declare(strict_types=1);

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use App\Models\Room;
use Doctrine\DBAL\Schema\Column;
use Eloquent;
use Throwable;

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
     * @throws Throwable
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function filterRules(/** @noinspection PhpUnusedParameterInspection */ array $rules, string $name, Column $column): array
    {
        if ('reservations.end_date' === $name) {
            $rules[] = 'after_or_equal:reservations.start_date';
        }
        if ('reservations.number' === $name) {
            $rules['min'] = 'min:1';

            $roomId = $this->getRoomId();
            if ($roomId) {
                $room = Room::find($roomId);
                if ($room) {
                    $rules['max'] = 'max:'.$room->number;
                }
            }
        }

        return $this->addReservationRule($rules, $name);
    }

    /**
     * @param  array  $rules
     * @param  string  $name
     *
     * @return array
     */
    private function addReservationRule(array $rules, string $name)
    {
        if ($this->has('reservations.start_date')) {
            if ('reservations.start_date' === $name) {
                $rules[] = 'reservation_term';
                $rules[] = 'reservation_availability';
                $rules[] = 'reservation_duplicate';
            }
        } elseif ($this->has('reservations.end_date')) {
            if ('reservations.end_date' === $name) {
                $rules[] = 'reservation_term';
                $rules[] = 'reservation_availability';
                $rules[] = 'reservation_duplicate';
            }
        } elseif ($this->has('reservations.room_id')) {
            if ('reservations.room_id' === $name) {
                $rules[] = 'reservation_availability';
            }
        } elseif ($this->has('reservations.guest_id')) {
            if ('reservations.guest_id' === $name) {
                $rules[] = 'reservation_duplicate';
            }
        }

        return $rules;
    }

    /**
     * @return mixed
     */
    abstract protected function getRoomId();
}
