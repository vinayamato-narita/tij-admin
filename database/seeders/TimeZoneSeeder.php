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
                "timezone_name_english" => "Osaka,Sapporo,Tokyo",
                "timezone_name_native" => "(GMT +09:00) 大阪、札幌、東京",
                "display_no" => "1",
                "diff_time" => "9"
            ],

            [
                "timezone_name_english" => "Taipei",
                "timezone_name_native" => "(GMT +08:00) 中华台北",
                "display_no" => "2",
                "diff_time" => "8"
            ],


            [
                "timezone_name_english" => "International Date Line West",
                "timezone_name_native" => "(GMT-12:00) 日付変更線西",
                "display_no" => "3",
                "diff_time" => "-12"
            ],

            [
                "timezone_name_english" => "Midway Island, Samoa",
                "timezone_name_native" => "(GMT-11:00) サモア、ミッドウェー島",
                "display_no" => "4",
                "diff_time" => "-11"
            ],

            [
                "timezone_name_english" => "Hawaii",
                "timezone_name_native" => "(GMT-10:00) ハワイ",
                "display_no" => "5",
                "diff_time" => "-10"
            ],

            [
                "timezone_name_english" => "Alaska",
                "timezone_name_native" => "(GMT-09:00) アラスカ",
                "display_no" => "6",
                "diff_time" => "-9"
            ],
            [
                "timezone_name_english" => "Pacific Time (US & Canada); Tijuana",
                "timezone_name_native" => "(GMT-08:00) 太平洋標準時（米国およびカナダ）; ティファナ",
                "display_no" => "7",
                "diff_time" => "-8"
            ],
            [
                "timezone_name_english" => "Arizona",
                "timezone_name_native" => "(GMT-07:00) アリゾナ",
                "display_no" => "8",
                "diff_time" => "-7"
            ],


            [
                "timezone_name_english" => "Chihuahua, La Paz, Mazatlan",
                "timezone_name_native" => "(GMT-07:00) チワワ、ラパス、マサトラン",
                "display_no" => "9",
                "diff_time" => "-7"
            ],

            [
                "timezone_name_english" => " Mountain Time (US & Canada)",
                "timezone_name_native" => "(GMT-07:00) 山岳部標準時（米国およびカナダ）",
                "display_no" => "10",
                "diff_time" => "-7"
            ],

            [
                "timezone_name_english" => "Central America",
                "timezone_name_native" => "(GMT-06:00) 中米",
                "display_no" => "11",
                "diff_time" => "-6"
            ],

            [
                "timezone_name_english" => "Central Time (US & Canada)",
                "timezone_name_native" => "(GMT-06:00) 中部標準時（米国およびカナダ）",
                "display_no" => "12",
                "diff_time" => "-6"
            ],

            [
                "timezone_name_english" => "Guadalajara, Mexico City, Monterrey",
                "timezone_name_native" => "(GMT-06:00) グアダラハラ、メキシコシティ、モンテレー",
                "display_no" => "13",
                "diff_time" => "-6"
            ],

            [
                "timezone_name_english" => "Saskatchewan",
                "timezone_name_native" => "(GMT-06:00) サスカチュワン",
                "display_no" => "14",
                "diff_time" => "-6"
            ],

            [
                "timezone_name_english" => "Bogota, Lima, Quito",
                "timezone_name_native" => "(GMT-05:00) ボゴタ、リマ、キト",
                "display_no" => "15",
                "diff_time" => "-5"
            ],

            [
                "timezone_name_english" => "Eastern Time (US & Canada)",
                "timezone_name_native" => "(GMT-05:00) 東部標準時（米国およびカナダ）",
                "display_no" => "16",
                "diff_time" => "-5"
            ],

            [
                "timezone_name_english" => "Indiana (East)",
                "timezone_name_native" => "(GMT-05:00) インディアナ（東）",
                "display_no" => "17",
                "diff_time" => "-5"
            ],

            [
                "timezone_name_english" => " Atlantic Time (Canada)",
                "timezone_name_native" => "(GMT-04:00) 大西洋標準時（カナダ）",
                "display_no" => "18",
                "diff_time" => "-4"
            ],

            [
                "timezone_name_english" => " Caracas, La Paz",
                "timezone_name_native" => "(GMT-04:00) カラカス、ラパス",
                "display_no" => "19",
                "diff_time" => "-4"
            ],


            [
                "timezone_name_english" => " Santiago",
                "timezone_name_native" => "(GMT-04:00) サンティアゴ",
                "display_no" => "20",
                "diff_time" => "-4"
            ],


            [
                "timezone_name_english" => "Newfoundland",
                "timezone_name_native" => "(GMT-03:30) ニューファンドランド",
                "display_no" => "21",
                "diff_time" => "-3.50"
            ],


            [
                "timezone_name_english" => "Brasilia",
                "timezone_name_native" => "(GMT-03:00) ブラジリア",
                "display_no" => "22",
                "diff_time" => "-3"
            ],


            [
                "timezone_name_english" => "Buenos Aires, Georgetown",
                "timezone_name_native" => "GMT-03:00) ブエノスアイレス、ジョージタウン",
                "display_no" => "23",
                "diff_time" => "-3"
            ],


            [
                "timezone_name_english" => "Greenland",
                "timezone_name_native" => "(GMT-03:00) グリーンランド",
                "display_no" => "24",
                "diff_time" => "-3"
            ],


            [
                "timezone_name_english" => "Mid-Atlantic",
                "timezone_name_native" => "(GMT-02:00) 中部大西洋岸",
                "display_no" => "25",
                "diff_time" => "-2"
            ],


            [
                "timezone_name_english" => "Azores",
                "timezone_name_native" => "(GMT-01:00) アゾレス諸島",
                "display_no" => "26",
                "diff_time" => "-1"
            ],


            [
                "timezone_name_english" => "Cape Verde Is. ",
                "timezone_name_native" => "(GMT-01:00) カーボベルデです。",
                "display_no" => "27",
                "diff_time" => "-1"
            ],


            [
                "timezone_name_english" => "Casablanca, Monrovia",
                "timezone_name_native" => "(GMT) カサブランカ、モンロビア",
                "display_no" => "28",
                "diff_time" => "0"
            ],


            [
                "timezone_name_english" => "(GMT) Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London",
                "timezone_name_native" => "（GMT）グリニッジ標準時：ダブリン、エジンバラ、リスボン、ロンドン",
                "display_no" => "29",
                "diff_time" => "0"
            ],


            [
                "timezone_name_english" => "Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna",
                "timezone_name_native" => "(GMT+01:00) アムステルダム、ベルリン、ベルン、ローマ、ストックホルム、ウィーン",
                "display_no" => "30",
                "diff_time" => "1"
            ],



            [
                "timezone_name_english" => "Belgrade, Bratislava, Budapest, Ljubljana, Prague",
                "timezone_name_native" => "(GMT+01:00) ベオグラード、ブラチスラバ、ブダペスト、リュブリャナ、プラハ",
                "display_no" => "31",
                "diff_time" => "1"
            ],


            [
                "timezone_name_english" => "Brussels, Copenhagen, Madrid, Paris",
                "timezone_name_native" => "(GMT+01:00) ブリュッセル、コペンハーゲン、マドリッド、パリ",
                "display_no" => "32",
                "diff_time" => "1"
            ],

            [
                "timezone_name_english" => "Sarajevo, Skopje, Warsaw, Zagreb",
                "timezone_name_native" => "(GMT+01:00) サラエボ、スコピエ、ワルシャワ、ザグレブ",
                "display_no" => "33",
                "diff_time" => "1"
            ],


            [
                "timezone_name_english" => "West Central Africa",
                "timezone_name_native" => "(GMT+01:00) 西中央アフリカ",
                "display_no" => "34",
                "diff_time" => "1"
            ],


            [
                "timezone_name_english" => "Athens, Beirut, Istanbul, Minsk",
                "timezone_name_native" => "(GMT+02:00) アテネ、ベイルート、イスタンブール、ミンスク",
                "display_no" => "35",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "Bucharest",
                "timezone_name_native" => "(GMT+02:00) ブカレスト",
                "display_no" => "36",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "Cairo",
                "timezone_name_native" => "(GMT+02:00) カイロ",
                "display_no" => "37",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "Harare, Pretoria",
                "timezone_name_native" => "(GMT+02:00) プレトリア、ハラレ",
                "display_no" => "38",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius",
                "timezone_name_native" => "(GMT+02:00) ヘルシンキ、キエフ、リガ、ソフィア、タリン、ビリニュス",
                "display_no" => "39",
                "diff_time" => "2"
            ],

            [
                "timezone_name_english" => "Jerusalem",
                "timezone_name_native" => "(GMT+02:00) エルサレム",
                "display_no" => "40",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "Baghdad",
                "timezone_name_native" => "(GMT+03:00) バグダッド",
                "display_no" => "41",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "(GMT+03:00) Kuwait, Riyadh",
                "timezone_name_native" => "(GMT+03:00) クウェート、リヤド",
                "display_no" => "42",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "Moscow, St. Petersburg, Volgograd",
                "timezone_name_native" => "(GMT+03:00) モスクワ、サンクトペテルブルク、ヴォルゴグラード",
                "display_no" => "43",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "Nairobi",
                "timezone_name_native" => "(GMT+03:00) ナイロビ",
                "display_no" => "44",
                "diff_time" => "3"
            ],

            [
                "timezone_name_english" => "Tehran",
                "timezone_name_native" => "(GMT+03:30) テヘラン",
                "display_no" => "45",
                "diff_time" => "3.50"
            ],

            [
                "timezone_name_english" => "Abu Dhabi, Muscat",
                "timezone_name_native" => "(GMT+04:00) アブダビ、マスカット",
                "display_no" => "46",
                "diff_time" => "4"
            ],

            [
                "timezone_name_english" => "Baku, Tbilisi, Yerevan",
                "timezone_name_native" => "(GMT+04:00) バクー、トビリシ、エレバン",
                "display_no" => "47",
                "diff_time" => "4"
            ],

            [
                "timezone_name_english" => "Kabul",
                "timezone_name_native" => "(GMT+04:30) カブール",
                "display_no" => "48",
                "diff_time" => "4.50"
            ],

            [
                "timezone_name_english" => "Ekaterinburg",
                "timezone_name_native" => "(GMT+05:00) エカテリンブルク",
                "display_no" => "49",
                "diff_time" => "5"
            ],

            [
                "timezone_name_english" => "Islamabad, Karachi, Tashkent",
                "timezone_name_native" => "(GMT+05:00) イスラマバード、カラチ、タシケント",
                "display_no" => "50",
                "diff_time" => "5"
            ],

            [
                "timezone_name_english" => "Chennai, Kolkata, Mumbai, New Delhi",
                "timezone_name_native" => "(GMT+05:30) チェンナイ、コルカタ、ムンバイ、ニューデリー",
                "display_no" => "51",
                "diff_time" => "5.50"
            ],

            [
                "timezone_name_english" => "Kathmandu",
                "timezone_name_native" => "(GMT+05:45) カトマンズ",
                "display_no" => "52",
                "diff_time" => "5.75"
            ],

            [
                "timezone_name_english" => "Almaty, Novosibirsk",
                "timezone_name_native" => "(GMT+06:00) アルマトイ、ノボシビルスク",
                "display_no" => "53",
                "diff_time" => "6"
            ],

            [
                "timezone_name_english" => "Astana, Dhaka",
                "timezone_name_native" => "(GMT+06:00) アスタナ、ダッカ",
                "display_no" => "54",
                "diff_time" => "6"
            ],

            [
                "timezone_name_english" => "Sri Jayawardenepura",
                "timezone_name_native" => "(GMT+06:00) スリジャヤワルダナプラ",
                "display_no" => "55",
                "diff_time" => "6"
            ],

            [
                "timezone_name_english" => "Rangoon",
                "timezone_name_native" => "(GMT+06:30) ラングーン",
                "display_no" => "56",
                "diff_time" => "6.50"
            ],

            [
                "timezone_name_english" => "Bangkok, Hanoi, Jakarta",
                "timezone_name_native" => "(GMT+07:00) バンコク、ハノイ、ジャカルタ",
                "display_no" => "57",
                "diff_time" => "7"
            ],

            [
                "timezone_name_english" => "Krasnoyarsk",
                "timezone_name_native" => "(GMT+07:00) クラスノヤルスク",
                "display_no" => "58",
                "diff_time" => "7"
            ],

            [
                "timezone_name_english" => "Beijing, Chongqing, Hong Kong, Urumqi",
                "timezone_name_native" => "(GMT+08:00) 北京、重慶、香港、ウルムチ",
                "display_no" => "59",
                "diff_time" => "8"
            ],

            [
                "timezone_name_english" => "Irkutsk, Ulaan Bataar",
                "timezone_name_native" => "(GMT+08:00) イルクーツク、ウランバタール",
                "display_no" => "60",
                "diff_time" => "8"
            ],

            [
                "timezone_name_english" => "Kuala Lumpur, Singapore",
                "timezone_name_native" => "(GMT+08:00) クアラルンプール、シンガポール",
                "display_no" => "61",
                "diff_time" => "8"
            ],

            [
                "timezone_name_english" => "Perth",
                "timezone_name_native" => "(GMT+08:00) パース",
                "display_no" => "62",
                "diff_time" => "8"
            ],

            [
                "timezone_name_english" => " Seoul",
                "timezone_name_native" => "(GMT+09:00) ソウル",
                "display_no" => "63",
                "diff_time" => "9"
            ],

            [
                "timezone_name_english" => "Vakutsk",
                "timezone_name_native" => "(GMT+09:00) ヴァクツク",
                "display_no" => "64",
                "diff_time" => "9"
            ],

            [
                "timezone_name_english" => "Adelaide",
                "timezone_name_native" => "(GMT+09:30) アデレード",
                "display_no" => "65",
                "diff_time" => "9.50"
            ],

            [
                "timezone_name_english" => "Darwin",
                "timezone_name_native" => "(GMT+09:30) ダーウィン",
                "display_no" => "66",
                "diff_time" => "9.50"
            ],


            [
                "timezone_name_english" => "Brisbane",
                "timezone_name_native" => "(GMT+10:00) ブリスベン",
                "display_no" => "67",
                "diff_time" => "10"
            ],


            [
                "timezone_name_english" => "Canberra, Melbourne, Sydney",
                "timezone_name_native" => "(GMT+10:00) キャンベラ、メルボルン、シドニー",
                "display_no" => "68",
                "diff_time" => "10"
            ],


            [
                "timezone_name_english" => "Guam, Port Moresby",
                "timezone_name_native" => "(GMT+10:00) グアム、ポートモレスビー",
                "display_no" => "69",
                "diff_time" => "10"
            ],


            [
                "timezone_name_english" => " Hobart",
                "timezone_name_native" => "(GMT+10:00) ホバート",
                "display_no" => "70",
                "diff_time" => "10"
            ],

            [
                "timezone_name_english" => "Vladivostok",
                "timezone_name_native" => "(GMT+10:00) ウラジオストク",
                "display_no" => "71",
                "diff_time" => "10"
            ],

            [
                "timezone_name_english" => " Magadan, Solomon Is., New Caledonia",
                "timezone_name_native" => "(GMT+11:00) マガダン、ソロモン島、ニューカレドニア",
                "display_no" => "72",
                "diff_time" => "11"
            ],

            [
                "timezone_name_english" => "Auckland, Wellington",
                "timezone_name_native" => "(GMT+12:00) オークランド、ウェリントン",
                "display_no" => "73",
                "diff_time" => "12"
            ],

            [
                "timezone_name_english" => "Fiji, Kamchatka, Marshall Is.",
                "timezone_name_native" => "(GMT+12:00) フィジー、カムチャツカ、マーシャル島",
                "display_no" => "74",
                "diff_time" => "12"
            ],

            [
                "timezone_name_english" => "Nuku\'alofa",
                "timezone_name_native" => "(GMT+13:00) ヌクアロファ",
                "display_no" => "75",
                "diff_time" => "13"
            ],


        ];

        foreach ($insertArr as  $i) {
            DB::table('timezone')->insert($i);
        }
    }
}
