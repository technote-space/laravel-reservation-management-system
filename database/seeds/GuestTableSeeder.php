<?php

use App\Helpers\Traits\FileHelper;
use Illuminate\Database\Seeder;
use App\Models\Guest;
use App\Models\GuestDetail;

class GuestTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper, FileHelper;

    /**
     * @return void
     */
    public function run()
    {
        factory(Guest::class, $this->getValue('number.guest'))->create()->each(function (Guest $guest) {
            factory(GuestDetail::class)->create([
                'guest_id' => $guest->id,
            ]);
        });
    }
}
