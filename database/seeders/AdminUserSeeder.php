<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $supper = new AdminUser();
        $supper->name = "admin";
        $supper->admin_user_email = "admin@gmail.com";
        $supper->admin_user_password = Hash::make('12345678');
        $supper->save();
    }
}
