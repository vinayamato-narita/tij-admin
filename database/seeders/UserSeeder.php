<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\DB;
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
        DB::table('admin_user')->truncate();
        $user = new AdminUser();
        $user->admin_user_name = "admin";
        $user->admin_user_email = "admin@gmail.com";
        $user->password = Hash::make('12345678');
        $user->save();
    }
}
