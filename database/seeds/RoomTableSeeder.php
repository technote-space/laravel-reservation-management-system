<?php

use App\Helpers\Traits\FileHelper;
use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper, Seeds\Traits\MasterTableSeeder, FileHelper;

    /**
     * @return string|Eloquent
     */
    protected function getTarget()
    {
        return Room::class;
    }

    /**
     * @param  array  $row
     *
     * @return array
     */
    protected function converter(array $row): array
    {
        return [
            'name'   => $row[0],
            'number' => $row[1],
            'price'  => $row[2],
        ];
    }

    /**
     * @return void
     */
    public function run()
    {
        factory(Room::class, $this->getValue('number.room'))->create();

        $this->import();
    }
}
