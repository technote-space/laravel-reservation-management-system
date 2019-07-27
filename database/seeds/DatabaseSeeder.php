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

        $this->call(AdminsTableSeeder::class);
        $this->call(GuestsTableSeeder::class);
        $this->call(RoomsTableSeeder::class);
        $this->call(ReservationsTableSeeder::class);
    }
}
