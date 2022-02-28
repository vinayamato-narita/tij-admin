<?php

namespace Database\Seeders;

use App\Models\ZoomSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoomSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('zoom_setting')->truncate();

        $zoomSetting = new ZoomSetting();
        $zoomSetting->join_before_host = 1;
        $zoomSetting->auto_recording = 1;
        $zoomSetting->waiting_room = 1;
        $zoomSetting->save();
    }
}
