<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminTableSeeder extends Seeder
{
    use Seeds\Traits\SeederHelper;

    /**
     * @return void
     */
    public function run()
    {
        factory(Admin::class, $this->getValue('admin_number'))->create();

        $name     = $this->getValue('admin_name');
        $email    = $this->getValue('admin_email');
        $password = $this->getValue('admin_password', 'password');
        if ($name && $email && $password) {
            factory(Admin::class)->create([
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make($password),
            ]);
        }
    }
}
