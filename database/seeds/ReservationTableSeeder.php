<?php

use App\Helpers\Traits\FileHelper;
use App\Models\ReservationDetail;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Support\Collection;

class ReservationTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper, FileHelper;

    /**
     * @return void
     */
    public function run()
    {
        $rooms = Room::all();
        Guest::all()->each(function (Guest $guest) use ($rooms) {
            Collection::times($this->getValue('number.reservation'), function () use ($guest, $rooms) {
                $reservation = factory(Reservation::class)->create([
                    'guest_id' => $guest->id,
                    'room_id'  => $rooms->random()->id,
                ]);
                factory(ReservationDetail::class)->create([
                    'reservation_id' => $reservation->id,
                    'payment'        => $reservation->start_date->isBefore(now()) ? $reservation->room->price : null,
                ]);
            });
        });
    }
}
