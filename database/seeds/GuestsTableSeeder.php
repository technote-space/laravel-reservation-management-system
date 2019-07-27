<?php

use Illuminate\Database\Seeder;
use App\Models\Guest;
use App\Models\GuestDetail;

class GuestsTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper;

    /**
     * @return void
     */
    public function run()
    {
        factory(Guest::class, $this->getValue('guest_number'))->create()->each(function (Guest $guest) {
            factory(GuestDetail::class)->create([
                'guest_id' => $guest->id,
            ]);
        });
    }
}
