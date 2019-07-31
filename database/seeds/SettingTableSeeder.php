<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper;

    /**
     * @return void
     */
    public function run()
    {
        factory(Setting::class)->create([
            'key'   => 'max_day',
            'value' => config("reservation.max_day"),
            'type'  => 'int',
        ]);
        factory(Setting::class)->create([
            'key'   => 'check_in',
            'value' => config("reservation.check_in"),
            'type'  => 'time',
        ]);
        factory(Setting::class)->create([
            'key'   => 'check_out',
            'value' => config("reservation.check_out"),
            'type'  => 'time',
        ]);
    }
}
