<?php

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Support\Collection;

class ReservationTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper;

    /**
     * @return void
     */
    public function run()
    {
        $rooms = Room::all();
        Guest::all()->each(function (Guest $guest) use ($rooms) {
            Collection::times($this->getValue('reservation_number'), function () use ($guest, $rooms) {
                factory(Reservation::class)->create([
                    'guest_id' => $guest->id,
                    'room_id'  => $rooms->random()->id,
                ]);
            });
        });
    }
}
