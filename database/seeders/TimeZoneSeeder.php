<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('timezone')->truncate();
        $insertArr = [
            [
                "timezone_name_english" => "(GMT +09:00) Osaka,Sapporo,Tokyo",
                "timezone_name_native" => "(GMT +09:00) 大阪、札幌、東京",
                "display_no" => "1",
                "diff_time" => "9"
            ],

            [
                "timezone_name_english" => "(GMT +08:00) Taipei",
                "timezone_name_native" => "(GMT +08:00) 中华台北",
                "display_no" => "2",
                "diff_time" => "8"
            ],


            [
                "timezone_name_english" => "(GMT-12:00) International Date Line West",
                "timezone_name_native" => "(GMT-12:00) International Date Line West",
                "display_no" => "3",
                "diff_time" => "-12"
            ],

            [
                "timezone_name_english" => "(GMT-11:00) Midway Island, Samoa",
                "timezone_name_native" => "(GMT-11:00) Midway Island, Samoa",
                "display_no" => "4",
                "diff_time" => "-11"
            ],

            [
                "timezone_name_english" => "(GMT-10:00) Hawaii",
                "timezone_name_native" => "(GMT-10:00) Hawaii",
                "display_no" => "5",
                "diff_time" => "-10"
            ],

            [
                "timezone_name_english" => "(GMT-09:00) Alaska",
                "timezone_name_native" => "(GMT-09:00) Alaska",
                "display_no" => "6",
                "diff_time" => "-9"
            ],
            [
                "timezone_name_english" => "(GMT-08:00) Pacific Time (US & Canada); Tijuana",
                "timezone_name_native" => "(GMT-08:00) Pacific Time (US & Canada); Tijuana",
                "display_no" => "7",
                "diff_time" => "-8"
            ],
            [
                "timezone_name_english" => "(GMT-07:00) Arizona",
                "timezone_name_native" => "(GMT-07:00) Arizona",
                "display_no" => "8",
                "diff_time" => "-7"
            ],


            [
                "timezone_name_english" => "(GMT-07:00) Chihuahua, La Paz, Mazatlan",
                "timezone_name_native" => "(GMT-07:00) Chihuahua, La Paz, Mazatlan",
                "display_no" => "9",
                "diff_time" => "-7"
            ],

            [
                "timezone_name_english" => "(GMT-07:00) Mountain Time (US & Canada)",
                "timezone_name_native" => "(GMT-07:00) Mountain Time (US & Canada)",
                "display_no" => "10",
                "diff_time" => "-7"
            ],

            [
                "timezone_name_english" => "(GMT-06:00) Central America",
                "timezone_name_native" => "(GMT-06:00) Central America",
                "display_no" => "11",
                "diff_time" => "-6"
            ],

            [
                "timezone_name_english" => "(GMT-06:00) Central Time (US & Canada)",
                "timezone_name_native" => "(GMT-06:00) Central Time (US & Canada)",
                "display_no" => "12",
                "diff_time" => "-6"
            ],

            [
                "timezone_name_english" => "(GMT-06:00) Guadalajara, Mexico City, Monterrey",
                "timezone_name_native" => "(GMT-06:00) Guadalajara, Mexico City, Monterrey",
                "display_no" => "13",
                "diff_time" => "-6"
            ],

            [
                "timezone_name_english" => "(GMT-06:00) Saskatchewan",
                "timezone_name_native" => "(GMT-06:00) Saskatchewan",
                "display_no" => "14",
                "diff_time" => "-6"
            ],

            [
                "timezone_name_english" => "(GMT-05:00) Bogota, Lima, Quito",
                "timezone_name_native" => "(GMT-05:00) Bogota, Lima, Quito",
                "display_no" => "15",
                "diff_time" => "-5"
            ],

            [
                "timezone_name_english" => "(GMT-05:00) Eastern Time (US & Canada)",
                "timezone_name_native" => "(GMT-05:00) Eastern Time (US & Canada)",
                "display_no" => "16",
                "diff_time" => "-5"
            ],

            [
                "timezone_name_english" => "(GMT-05:00) Indiana (East",
                "timezone_name_native" => "(GMT-05:00) Indiana (East",
                "display_no" => "17",
                "diff_time" => "-5"
            ],

            [
                "timezone_name_english" => "(GMT-04:00) Atlantic Time (Canada)",
                "timezone_name_native" => "(GMT-04:00) Atlantic Time (Canada)",
                "display_no" => "18",
                "diff_time" => "-4"
            ],

            [
                "timezone_name_english" => "(GMT-04:00) Caracas, La Paz",
                "timezone_name_native" => "(GMT-04:00) Caracas, La Paz",
                "display_no" => "19",
                "diff_time" => "-4"
            ],


            [
                "timezone_name_english" => "(GMT-04:00) Santiago",
                "timezone_name_native" => "(GMT-04:00) Santiago",
                "display_no" => "20",
                "diff_time" => "-4"
            ],


            [
                "timezone_name_english" => "(GMT-03:30) Newfoundland",
                "timezone_name_native" => "(GMT-03:30) Newfoundland",
                "display_no" => "21",
                "diff_time" => "-3.50"
            ],


            [
                "timezone_name_english" => "(GMT-03:00) Brasilia",
                "timezone_name_native" => "(GMT-03:00) Brasilia",
                "display_no" => "22",
                "diff_time" => "-3"
            ],


            [
                "timezone_name_english" => "GMT-03:00) Buenos Aires, Georgetown",
                "timezone_name_native" => "GMT-03:00) Buenos Aires, Georgetown",
                "display_no" => "23",
                "diff_time" => "-3"
            ],


            [
                "timezone_name_english" => "(GMT-03:00) Greenland",
                "timezone_name_native" => "(GMT-03:00) Greenland",
                "display_no" => "24",
                "diff_time" => "-3"
            ],


            [
                "timezone_name_english" => "(GMT-02:00) Mid-Atlantic",
                "timezone_name_native" => "(GMT-02:00) Mid-Atlantic",
                "display_no" => "25",
                "diff_time" => "-2"
            ],


            [
                "timezone_name_english" => "(GMT-01:00) Azores",
                "timezone_name_native" => "(GMT-01:00) Azores",
                "display_no" => "26",
                "diff_time" => "-1"
            ],


            [
                "timezone_name_english" => "(GMT-01:00) Cape Verde Is.",
                "timezone_name_native" => "(GMT-01:00) Cape Verde Is.",
                "display_no" => "27",
                "diff_time" => "-1"
            ],


            [
                "timezone_name_english" => "(GMT) Casablanca, Monrovia",
                "timezone_name_native" => "(GMT) Casablanca, Monrovia",
                "display_no" => "28",
                "diff_time" => "0"
            ],


            [
                "timezone_name_english" => "(GMT) Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London",
                "timezone_name_native" => "(GMT) Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London",
                "display_no" => "29",
                "diff_time" => "0"
            ],


            [
                "timezone_name_english" => "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
                "timezone_name_native" => "(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
                "display_no" => "30",
                "diff_time" => "1"
            ],



            [
                "timezone_name_english" => "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague",
                "timezone_name_native" => "(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague",
                "display_no" => "31",
                "diff_time" => "1"
            ],


            [
                "timezone_name_english" => "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris",
                "timezone_name_native" => "(GMT+01:00) Brussels, Copenhagen, Madrid, Paris",
                "display_no" => "32",
                "diff_time" => "1"
            ],

            [
                "timezone_name_english" => "(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb",
                "timezone_name_native" => "(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb",
                "display_no" => "33",
                "diff_time" => "1"
            ],


            [
                "timezone_name_english" => "(GMT+01:00) West Central Africa",
                "timezone_name_native" => "(GMT+01:00) West Central Africa",
                "display_no" => "34",
                "diff_time" => "1"
            ],


            [
                "timezone_name_english" => "(GMT+02:00) Athens, Beirut, Istanbul, Minsk",
                "timezone_name_native" => "(GMT+02:00) Athens, Beirut, Istanbul, Minsk",
                "display_no" => "35",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "(GMT+02:00) Bucharest",
                "timezone_name_native" => "(GMT+02:00) Bucharest",
                "display_no" => "36",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "(GMT+02:00) Cairo",
                "timezone_name_native" => "(GMT+02:00) Cairo",
                "display_no" => "37",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "(GMT+02:00) Harare, Pretoria",
                "timezone_name_native" => "(GMT+02:00) Harare, Pretoria",
                "display_no" => "38",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius",
                "timezone_name_native" => "(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius",
                "display_no" => "39",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "(GMT+02:00) Jerusalem', '(GMT+02:00) Jerusalem",
                "timezone_name_native" => "(GMT+02:00) Jerusalem', '(GMT+02:00) Jerusalem",
                "display_no" => "40",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "(GMT+03:00) Baghdad",
                "timezone_name_native" => "(GMT+03:00) Baghdad",
                "display_no" => "41",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "(GMT+03:00) Kuwait, Riyadh",
                "timezone_name_native" => "(GMT+03:00) Kuwait, Riyadh",
                "display_no" => "42",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "(GMT+03:00) Moscow, St. Petersburg, Volgograd",
                "timezone_name_native" => "(GMT+03:00) Moscow, St. Petersburg, Volgograd",
                "display_no" => "43",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "(GMT+03:00) Nairobi",
                "timezone_name_native" => "(GMT+03:00) Nairobi",
                "display_no" => "44",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "(GMT+03:30) Tehran",
                "timezone_name_native" => "(GMT+03:30) Tehran",
                "display_no" => "45",
                "diff_time" => "3.50"
            ],

            [
                "timezone_name_english" => "(GMT+04:00) Abu Dhabi, Muscat",
                "timezone_name_native" => "(GMT+04:00) Abu Dhabi, Muscat",
                "display_no" => "46",
                "diff_time" => "4"
            ],

            [
                "timezone_name_english" => "(GMT+04:00) Baku, Tbilisi, Yerevan",
                "timezone_name_native" => "(GMT+04:00) Baku, Tbilisi, Yerevan",
                "display_no" => "47",
                "diff_time" => "4"
            ],

            [
                "timezone_name_english" => "(GMT+04:30) Kabul",
                "timezone_name_native" => "(GMT+04:30) Kabul",
                "display_no" => "48",
                "diff_time" => "4.50"
            ],

            [
                "timezone_name_english" => "(GMT+05:00) Ekaterinburg",
                "timezone_name_native" => "(GMT+05:00) Ekaterinburg",
                "display_no" => "49",
                "diff_time" => "5"
            ],

            [
                "timezone_name_english" => "(GMT+05:00) Islamabad, Karachi, Tashkent",
                "timezone_name_native" => "(GMT+05:00) Islamabad, Karachi, Tashkent",
                "display_no" => "50",
                "diff_time" => "5"
            ],

            [
                "timezone_name_english" => "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
                "timezone_name_native" => "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi",
                "display_no" => "51",
                "diff_time" => "5.50"
            ],

            [
                "timezone_name_english" => "(GMT+05:45) Kathmandu",
                "timezone_name_native" => "(GMT+05:45) Kathmandu",
                "display_no" => "52",
                "diff_time" => "5.75"
            ],

            [
                "timezone_name_english" => "(GMT+06:00) Almaty, Novosibirsk",
                "timezone_name_native" => "(GMT+06:00) Almaty, Novosibirsk",
                "display_no" => "53",
                "diff_time" => "6"
            ],

            [
                "timezone_name_english" => "(GMT+06:00) Astana, Dhaka",
                "timezone_name_native" => "(GMT+06:00) Astana, Dhaka",
                "display_no" => "54",
                "diff_time" => "6"
            ],

            [
                "timezone_name_english" => "(GMT+06:00) Sri Jayawardenepura",
                "timezone_name_native" => "(GMT+06:00) Sri Jayawardenepura",
                "display_no" => "55",
                "diff_time" => "6"
            ],

            [
                "timezone_name_english" => "(GMT+06:30) Rangoon",
                "timezone_name_native" => "(GMT+06:30) Rangoon",
                "display_no" => "56",
                "diff_time" => "6.50"
            ],

            [
                "timezone_name_english" => "(GMT+07:00) Bangkok, Hanoi, Jakarta",
                "timezone_name_native" => "(GMT+07:00) Bangkok, Hanoi, Jakarta",
                "display_no" => "57",
                "diff_time" => "7"
            ],

            [
                "timezone_name_english" => "(GMT+07:00) Krasnoyarsk",
                "timezone_name_native" => "(GMT+07:00) Krasnoyarsk",
                "display_no" => "58",
                "diff_time" => "7"
            ],

            [
                "timezone_name_english" => "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
                "timezone_name_native" => "(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi",
                "display_no" => "59",
                "diff_time" => "8"
            ],

            [
                "timezone_name_english" => "(GMT+08:00) Irkutsk, Ulaan Bataar",
                "timezone_name_native" => "(GMT+08:00) Irkutsk, Ulaan Bataar",
                "display_no" => "60",
                "diff_time" => "8"
            ],

            [
                "timezone_name_english" => "(GMT+08:00) Kuala Lumpur, Singapore",
                "timezone_name_native" => "(GMT+08:00) Kuala Lumpur, Singapore",
                "display_no" => "61",
                "diff_time" => "8"
            ],

            [
                "timezone_name_english" => "(GMT+08:00) Perth",
                "timezone_name_native" => "(GMT+08:00) Perth",
                "display_no" => "62",
                "diff_time" => "8"
            ],

            [
                "timezone_name_english" => "(GMT+09:00) Seoul",
                "timezone_name_native" => "(GMT+09:00) Seoul",
                "display_no" => "63",
                "diff_time" => "9"
            ],

            [
                "timezone_name_english" => "(GMT+09:00) Vakutsk",
                "timezone_name_native" => "(GMT+09:00) Vakutsk",
                "display_no" => "64",
                "diff_time" => "9"
            ],

            [
                "timezone_name_english" => "(GMT+09:30) Adelaide",
                "timezone_name_native" => "(GMT+09:30) Adelaide",
                "display_no" => "65",
                "diff_time" => "9.50"
            ],

            [
                "timezone_name_english" => "(GMT+09:30) Darwin",
                "timezone_name_native" => "(GMT+09:30) Darwin",
                "display_no" => "66",
                "diff_time" => "9.50"
            ],


            [
                "timezone_name_english" => "(GMT+10:00) Brisbane",
                "timezone_name_native" => "(GMT+10:00) Brisbane",
                "display_no" => "67",
                "diff_time" => "10"
            ],


            [
                "timezone_name_english" => "(GMT+10:00) Canberra, Melbourne, Sydney",
                "timezone_name_native" => "(GMT+10:00) Canberra, Melbourne, Sydney",
                "display_no" => "68",
                "diff_time" => "10"
            ],


            [
                "timezone_name_english" => "(GMT+10:00) Guam, Port Moresby",
                "timezone_name_native" => "(GMT+10:00) Guam, Port Moresby",
                "display_no" => "69",
                "diff_time" => "10"
            ],


            [
                "timezone_name_english" => "(GMT+10:00) Hobart",
                "timezone_name_native" => "(GMT+10:00) Hobart",
                "display_no" => "70",
                "diff_time" => "10"
            ],

            [
                "timezone_name_english" => "(GMT+10:00) Vladivostok",
                "timezone_name_native" => "(GMT+10:00) Vladivostok",
                "display_no" => "71",
                "diff_time" => "10"
            ],

            [
                "timezone_name_english" => "(GMT+11:00) Magadan, Solomon Is., New Caledonia",
                "timezone_name_native" => "(GMT+11:00) Magadan, Solomon Is., New Caledonia",
                "display_no" => "72",
                "diff_time" => "11"
            ],

            [
                "timezone_name_english" => "(GMT+12:00) Auckland, Wellington",
                "timezone_name_native" => "(GMT+12:00) Auckland, Wellington",
                "display_no" => "73",
                "diff_time" => "12"
            ],

            [
                "timezone_name_english" => "(GMT+12:00) Fiji, Kamchatka, Marshall Is.",
                "timezone_name_native" => "(GMT+12:00) Fiji, Kamchatka, Marshall Is.",
                "display_no" => "74",
                "diff_time" => "12"
            ],

            [
                "timezone_name_english" => "(GMT+13:00) Nuku\'alofa",
                "timezone_name_native" => "(GMT+13:00) Nuku\'alofa",
                "display_no" => "75",
                "diff_time" => "13"
            ],


        ];

        foreach ($insertArr as  $i) {
            DB::table('timezone')->insert($i);
        }
    }
}
