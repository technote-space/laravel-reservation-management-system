<?php

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper;

    /**
     * @return void
     */
    public function run()
    {
        factory(Room::class, $this->getValue('room_number'))->create();
    }
}
