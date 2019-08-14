<?php

use App\Helpers\Traits\FileHelper;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper, Seeds\Traits\MasterTableSeeder, FileHelper;

    /**
     * @return string|Eloquent
     */
    protected function getTarget()
    {
        return Setting::class;
    }

    /**
     * @param  array  $row
     *
     * @return array
     */
    protected function converter(array $row): array
    {
        return [
            'key'   => $row[0],
            'value' => $row[1],
            'type'  => $row[2],
        ];
    }

    /**
     * @return void
     */
    public function run()
    {
        Setting::clearCache();
        $this->import();
    }
}
