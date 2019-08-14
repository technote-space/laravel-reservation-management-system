<?php

use App\Helpers\Traits\FileHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper, Seeds\Traits\MasterTableSeeder, FileHelper;

    /**
     * @return string|Eloquent
     */
    protected function getTarget()
    {
        return Admin::class;
    }

    /**
     * @param  array  $row
     *
     * @return array
     */
    protected function converter(array $row): array
    {
        return [
            'name'     => $row[0],
            'email'    => $row[1],
            'password' => Hash::make($row[2]),
        ];
    }

    /**
     * @return void
     */
    public function run()
    {
        factory(Admin::class, $this->getValue('number.admin'))->create();

        $this->import();
    }
}
