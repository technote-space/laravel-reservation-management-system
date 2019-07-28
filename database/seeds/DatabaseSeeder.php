<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reservations')->delete();
        DB::table('admins')->delete();
        DB::table('guests')->delete();
        DB::table('guest_details')->delete();
        DB::table('rooms')->delete();
        DB::table('settings')->delete();

        $this->call(AdminTableSeeder::class);
        $this->call(GuestTableSeeder::class);
        $this->call(RoomTableSeeder::class);
        $this->call(ReservationTableSeeder::class);
        $this->call(SettingTableSeeder::class);
    }
}
