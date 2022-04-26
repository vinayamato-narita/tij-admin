<?php

namespace Database\Seeders;

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
        $this->call(UserSeeder::class);
        $this->call(TestCategorySeeder::class);
        $this->call(ZoomSettingSeeder::class);
        $this->call(AdminUserRightSeeder::class);
        $this->call(FAQCategoryseeder::class);
        $this->call(NewsSubjectSeeder::class);
        $this->call(TimeZoneSeeder::class);
        $this->call(ImportMailPatternSeeder::class);
        // \App\Models\User::factory(10)->create();
    }
}
