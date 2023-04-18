<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModifieldTimeZoneInfo extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();


            $queries = "update timezone set timezone_name_english = '(GMT+09:00)Osaka,Sapporo,Tokyo/大阪、札幌、東京', timezone_name_native='(GMT+09:00)Osaka,Sapporo,Tokyo/大阪、札幌、東京' , display_no = 1 where timezone_id = 1;
update timezone set timezone_name_english = '(GMT+08:00)Taipei/中华台北' , timezone_name_native='(GMT+08:00)Taipei/中华台北' , display_no = 2 where timezone_id = 2;
update timezone set timezone_name_english = '(GMT-12:00)International Date Line West' , display_no = 6 where timezone_id = 3;
update timezone set timezone_name_english = '(GMT-11:00)Midway Island, Samoa' , display_no = 7 where timezone_id = 4;
update timezone set timezone_name_english = '(GMT-10:00)Hawaii' , display_no = 8 where timezone_id = 5;
update timezone set timezone_name_english = '(GMT-09:00)Alaska' , display_no = 9 where timezone_id = 6;
update timezone set timezone_name_english = '(GMT-08:00)Alaska (Summer time)' , display_no = 10 where timezone_id = 7;
update timezone set timezone_name_english = '(GMT-08:00)Pacific Time (US & Canada); Tijuana' , display_no = 11 where timezone_id = 8;
update timezone set timezone_name_english = '(GMT-07:00)Pacific Time (US & Canada); Tijuana (Summer time)' , display_no = 12 where timezone_id = 9;
update timezone set timezone_name_english = '(GMT-07:00)Arizona' , display_no = 13 where timezone_id = 10;
update timezone set timezone_name_english = '(GMT-07:00)Chihuahua, La Paz, Mazatlan' , display_no = 14 where timezone_id = 11;
update timezone set timezone_name_english = '(GMT-06:00)Chihuahua, La Paz, Mazatlan (Summer time)' , display_no = 15 where timezone_id = 12;
update timezone set timezone_name_english = '(GMT-07:00)Mountain Time (US & Canada)' , display_no = 16 where timezone_id = 13;
update timezone set timezone_name_english = '(GMT-06:00)Mountain Time (US & Canada) (Summer time)' , display_no = 17 where timezone_id = 14;
update timezone set timezone_name_english = '(GMT-06:00)Central America' , display_no = 18 where timezone_id = 15;
update timezone set timezone_name_english = '(GMT-05:00)Central America (Summer time)' , display_no = 19 where timezone_id = 16;
update timezone set timezone_name_english = '(GMT-06:00)Central Time (US & Canada)' , display_no = 20 where timezone_id = 17;
update timezone set timezone_name_english = '(GMT-05:00)Central Time (US & Canada) (Summer time)' , display_no = 21 where timezone_id = 18;
update timezone set timezone_name_english = '(GMT-06:00)Guadalajara, Mexico City, Monterrey' , display_no = 22 where timezone_id = 19;
update timezone set timezone_name_english = '(GMT-05:00)Guadalajara, Mexico City, Monterrey (Summer time)' , display_no = 23 where timezone_id = 20;
update timezone set timezone_name_english = '(GMT-06:00)Saskatchewan' , display_no = 24 where timezone_id = 21;
update timezone set timezone_name_english = '(GMT-05:00)Bogota, Lima, Quito' , display_no = 25 where timezone_id = 22;
update timezone set timezone_name_english = '(GMT-05:00)Eastern Time (US & Canada)' , display_no = 26 where timezone_id = 23;
update timezone set timezone_name_english = '(GMT-04:00)Eastern Time (US & Canada) (Summer time)' , display_no = 27 where timezone_id = 24;
update timezone set timezone_name_english = '(GMT-05:00)Indiana (East)' , display_no = 28 where timezone_id = 25;
update timezone set timezone_name_english = '(GMT-04:00)Indiana (East) (Summer time)' , display_no = 29 where timezone_id = 26;
update timezone set timezone_name_english = '(GMT-04:00)Atlantic Time (Canada)' , display_no = 30 where timezone_id = 27;
update timezone set timezone_name_english = '(GMT-03:00)Atlantic Time(Canada) (Summer time)' , display_no = 31 where timezone_id = 28;
update timezone set timezone_name_english = '(GMT-04:00)Caracas, La Paz' , display_no = 32 where timezone_id = 29;
update timezone set timezone_name_english = '(GMT-04:00)Santiago' , display_no = 33 where timezone_id = 30;
update timezone set timezone_name_english = '(GMT-03:00)Santiago (Summer time)' , display_no = 34 where timezone_id = 31;
update timezone set timezone_name_english = '(GMT-03:30)Newfoundland' , display_no = 35 where timezone_id = 32;
update timezone set timezone_name_english = '(GMT-02:30)Newfoundland (Summer time)' , display_no = 36 where timezone_id = 33;
update timezone set timezone_name_english = '(GMT-03:00)Brasilia' , display_no = 37 where timezone_id = 34;
update timezone set timezone_name_english = '(GMT-03:00)Buenos Aires, Georgetown' , display_no = 38 where timezone_id = 35;
update timezone set timezone_name_english = '(GMT-03:00)Greenland' , display_no = 39 where timezone_id = 36;
update timezone set timezone_name_english = '(GMT-02:00)Mid-Atlantic' , display_no = 40 where timezone_id = 37;
update timezone set timezone_name_english = '(GMT-01:00)Mid-Atlantic (Summer time)' , display_no = 41 where timezone_id = 38;
update timezone set timezone_name_english = '(GMT-01:00)Azores' , display_no = 42 where timezone_id = 39;
update timezone set timezone_name_english = '(GMT+00:00)Azores (Summer time)' , display_no = 43 where timezone_id = 40;
update timezone set timezone_name_english = '(GMT-01:00)Cape Verde Is. ' , display_no = 44 where timezone_id = 41;
update timezone set timezone_name_english = '(GMT+00:00)Casablanca, Monrovia' , display_no = 45 where timezone_id = 42;
update timezone set timezone_name_english = '(GMT+00:00)Greenwich Mean Time' , display_no = 46 where timezone_id = 43;
update timezone set timezone_name_english = '(GMT+00:00)Dublin, Edinburgh, Lisbon, London' , display_no = 47 where timezone_id = 44;
update timezone set timezone_name_english = '(GMT+01:00)Dublin, Edinburgh, Lisbon, London (Summer time)' , display_no = 48 where timezone_id = 45;
update timezone set timezone_name_english = '(GMT+01:00)Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna' , display_no = 49 where timezone_id = 46;
update timezone set timezone_name_english = '(GMT+02:00)Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna (Summer time)' , display_no = 50 where timezone_id = 47;
update timezone set timezone_name_english = '(GMT+01:00)Belgrade, Bratislava, Budapest, Ljubljana, Prague' , display_no = 51 where timezone_id = 48;
update timezone set timezone_name_english = '(GMT+02:00)Belgrade, Bratislava, Budapest, Ljubljana, Prague (Summer time)' , display_no = 52 where timezone_id = 49;
update timezone set timezone_name_english = '(GMT+01:00)Brussels, Copenhagen, Madrid, Paris' , display_no = 53 where timezone_id = 50;
update timezone set timezone_name_english = '(GMT+02:00)Brussels, Copenhagen, Madrid, Paris (Summer time)' , display_no = 54 where timezone_id = 51;
update timezone set timezone_name_english = '(GMT+01:00)Sarajevo, Skopje, Warsaw, Zagreb' , display_no = 55 where timezone_id = 52;
update timezone set timezone_name_english = '(GMT+02:00)Sarajevo, Skopje, Warsaw, Zagreb (Summer time)' , display_no = 56 where timezone_id = 53;
update timezone set timezone_name_english = '(GMT+01:00)West Central Africa' , display_no = 57 where timezone_id = 54;
update timezone set timezone_name_english = '(GMT+02:00)Athens, Beirut, Istanbul, Minsk' , display_no = 58 where timezone_id = 55;
update timezone set timezone_name_english = '(GMT+03:00)Athens, Beirut, Istanbul, Minsk (Summer time)' , display_no = 59 where timezone_id = 56;
update timezone set timezone_name_english = '(GMT+02:00)Bucharest' , display_no = 60 where timezone_id = 57;
update timezone set timezone_name_english = '(GMT+03:00)Bucharest (Summer time)' , display_no = 61 where timezone_id = 58;
update timezone set timezone_name_english = '(GMT+02:00)Cairo' , display_no = 62 where timezone_id = 59;
update timezone set timezone_name_english = '(GMT+02:00)Harare, Pretoria' , display_no = 63 where timezone_id = 60;
update timezone set timezone_name_english = '(GMT+02:00)Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius' , display_no = 64 where timezone_id = 61;
update timezone set timezone_name_english = '(GMT+03:00)Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius (Summer time)' , display_no = 65 where timezone_id = 62;
update timezone set timezone_name_english = '(GMT+02:00)Jerusalem' , display_no = 66 where timezone_id = 63;
update timezone set timezone_name_english = '(GMT+03:00)Jerusalem (Summer time)' , display_no = 67 where timezone_id = 64;
update timezone set timezone_name_english = '(GMT+03:00)Baghdad' , display_no = 68 where timezone_id = 65;
update timezone set timezone_name_english = '(GMT+03:00)Kuwait, Riyadh' , display_no = 69 where timezone_id = 66;
update timezone set timezone_name_english = '(GMT+03:00)Moscow, St. Petersburg, Volgograd' , display_no = 70 where timezone_id = 67;
update timezone set timezone_name_english = '(GMT+03:00)Nairobi' , display_no = 71 where timezone_id = 68;
update timezone set timezone_name_english = '(GMT+03:30)Tehran' , display_no = 72 where timezone_id = 69;
update timezone set timezone_name_english = '(GMT+04:30)Tehran (Summer time)' , display_no = 73 where timezone_id = 70;
update timezone set timezone_name_english = '(GMT+04:00)Abu Dhabi, Muscat' , display_no = 74 where timezone_id = 71;
update timezone set timezone_name_english = '(GMT+04:00)Baku, Tbilisi, Yerevan' , display_no = 75 where timezone_id = 72;
update timezone set timezone_name_english = '(GMT+04:30)Kabul' , display_no = 76 where timezone_id = 73;
update timezone set timezone_name_english = '(GMT+05:00)Ekaterinburg' , display_no = 77 where timezone_id = 74;
update timezone set timezone_name_english = '(GMT+05:30)Chennai, Kolkata, Mumbai, New Delhi' , display_no = 4 where timezone_id = 76;
update timezone set timezone_name_english = '(GMT+05:45)Kathmandu' , display_no = 79 where timezone_id = 77;
update timezone set timezone_name_english = '(GMT+06:00)Almaty, Novosibirsk' , display_no = 80 where timezone_id = 78;
update timezone set timezone_name_english = '(GMT+06:00)Astana, Dhaka' , display_no = 81 where timezone_id = 79;
update timezone set timezone_name_english = '(GMT+06:00)Sri Jayawardenepura' , display_no = 82 where timezone_id = 80;
update timezone set timezone_name_english = '(GMT+06:30)Rangoon' , display_no = 83 where timezone_id = 81;
update timezone set timezone_name_english = '(GMT+07:00)Bangkok, Hanoi, Jakarta' , display_no = 3 where timezone_id = 82;
update timezone set timezone_name_english = '(GMT+07:00)Krasnoyarsk' , display_no = 84 where timezone_id = 83;
update timezone set timezone_name_english = '(GMT+08:00)Beijing, Chongqing, Hong Kong, Urumqi/北京、重慶、香港、ウルムチ' ,timezone_name_native='(GMT+08:00)Beijing, Chongqing, Hong Kong, Urumqi/北京、重慶、香港、ウルムチ',  display_no = 85 where timezone_id = 84;
update timezone set timezone_name_english = '(GMT+08:00)Irkutsk, Ulaan Bataar' , display_no = 86 where timezone_id = 85;
update timezone set timezone_name_english = '(GMT+08:00)Kuala Lumpur, Singapore' , display_no = 87 where timezone_id = 86;
update timezone set timezone_name_english = '(GMT+08:00)Perth' , display_no = 88 where timezone_id = 87;
update timezone set timezone_name_english = '(GMT+09:00)Seoul' , display_no = 89 where timezone_id = 88;
update timezone set timezone_name_english = '(GMT+09:00)Vakutsk' , display_no = 90 where timezone_id = 89;
update timezone set timezone_name_english = '(GMT+09:30)Adelaide' , display_no = 91 where timezone_id = 90;
update timezone set timezone_name_english = '(GMT+10:30)Adelaide (Summer time)' , display_no = 92 where timezone_id = 91;
update timezone set timezone_name_english = '(GMT+09:30)Darwin' , display_no = 93 where timezone_id = 92;
update timezone set timezone_name_english = '(GMT+10:00)Brisbane' , display_no = 94 where timezone_id = 93;
update timezone set timezone_name_english = '(GMT+10:00)Canberra, Melbourne, Sydney' , display_no = 95 where timezone_id = 94;
update timezone set timezone_name_english = '(GMT+11:00)Canberra, Melbourne, Sydney (Summer time)' , display_no = 96 where timezone_id = 95;
update timezone set timezone_name_english = '(GMT+10:00)Guam, Port Moresby' , display_no = 97 where timezone_id = 96;
update timezone set timezone_name_english = '(GMT+10:00)Hobart' , display_no = 98 where timezone_id = 97;
update timezone set timezone_name_english = '(GMT+11:00)Hobart (Summer time)' , display_no = 99 where timezone_id = 98;
update timezone set timezone_name_english = '(GMT+10:00)Vladivostok' , display_no = 100 where timezone_id = 99;
update timezone set timezone_name_english = '(GMT+11:00)Magadan, Solomon Is., New Caledonia' , display_no = 101 where timezone_id = 100;
update timezone set timezone_name_english = '(GMT+12:00)Auckland, Wellington' , display_no = 102 where timezone_id = 101;
update timezone set timezone_name_english = '(GMT+13:00)Auckland, Wellington (Summer time)' , display_no = 103 where timezone_id = 102;
update timezone set timezone_name_english = '(GMT+12:00)Fiji, Kamchatka, Marshall Is.' , display_no = 104 where timezone_id = 103;
update timezone set timezone_name_english = '(GMT+13:00)Fiji, Kamchatka, Marshall Is. (Summer time)' , display_no = 105 where timezone_id = 104;
update timezone set timezone_name_english = '(GMT+13:00)Nukualofa' , display_no = 106 where timezone_id = 105;
";
            $querieArr = explode("\n", $queries);

            foreach ($querieArr as $q) {
                if (empty($q)) continue;
                DB::statement($q);
            }

            //insert new 1 timezone ( (GMT+08:00)Manila )
            DB::table('timezone')->where('timezone_name_english', '(GMT+08:00)Manila')->delete();
            DB::table('timezone')->insert(            [
                "timezone_name_english" => "(GMT+08:00)Manila",
                "timezone_name_native" => "(GMT+08:00)Manila",
                "display_no" => "5",
                "diff_time" => "8"
            ]);


            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack();
        }
    }
}
