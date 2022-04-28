<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('country')->truncate();
        $countries = [
            [
                'country_name' => 'AMERICAN - USA',
                'abbreviation' => 'US'
            ],
            [
                'country_name' => 'ARGENTINA',
                'abbreviation' => 'AR'
            ],
            [
                'country_name' => 'AUSTRALIA',
                'abbreviation' => 'AU'
            ],
            [
                'country_name' => 'BAHAMAS',
                'abbreviation' => 'BS'
            ],
            [
                'country_name' => 'BELGIUM',
                'abbreviation' => 'BE'
            ],
            [
                'country_name' => 'BRAZIL',
                'abbreviation' => 'BR'
            ],
            [
                'country_name' => 'CANADA',
                'abbreviation' => 'CA'
            ],
            [
                'country_name' => 'CHINA',
                'abbreviation' => 'CN'
            ],
            [
                'country_name' => 'COLOMBIA',
                'abbreviation' => 'CO'
            ],
            [
                'country_name' => 'CUBA',
                'abbreviation' => 'CU'
            ],
            [
                'country_name' => 'DOMINICAN REPUBLIC',
                'abbreviation' => 'DO'
            ],
            [
                'country_name' => 'ECUADOR',
                'abbreviation' => 'EC'
            ],
            [
                'country_name' => 'EL SALVADOR',
                'abbreviation' => 'SV'
            ],
            [
                'country_name' => 'FRANCE',
                'abbreviation' => 'FR'
            ],
            [
                'country_name' => 'GERMANY',
                'abbreviation' => 'DE'
            ],
            [
                'country_name' => 'GUATEMALA',
                'abbreviation' => 'GT'
            ],
            [
                'country_name' => 'HAITI',
                'abbreviation' => 'HT'
            ],
            [
                'country_name' => 'HONDURAS',
                'abbreviation' => 'HN'
            ],
            [
                'country_name' => 'INDIA',
                'abbreviation' => 'IN'
            ],
            [
                'country_name' => 'IRELAND',
                'abbreviation' => 'IE'
            ],
            [
                'country_name' => 'ISRAEL',
                'abbreviation' => 'IL'
            ],
            [
                'country_name' => 'ITALY',
                'abbreviation' => 'IT'
            ],
            [
                'country_name' => 'JAPAN',
                'abbreviation' => 'JP'
            ],
            [
                'country_name' => 'KOREA - REPUBLIC OF',
                'abbreviation' => 'KR'
            ],
            [
                'country_name' => 'MEXICO',
                'abbreviation' => 'MX'
            ],
            [
                'country_name' => 'NETHERLANDS',
                'abbreviation' => 'NL'
            ],
            [
                'country_name' => 'PHILIPPINES',
                'abbreviation' => 'PH'
            ],
            [
                'country_name' => 'SPAIN',
                'abbreviation' => 'ES'
            ],
            [
                'country_name' => 'SWEDEN',
                'abbreviation' => 'SE'
            ],
            [
                'country_name' => 'SWITZERLAND',
                'abbreviation' => 'CH'
            ],
            [
                'country_name' => 'TAIWAN - PROVINCE OF CHINA',
                'abbreviation' => 'TW'
            ],
            [
                'country_name' => 'UNITED KINGDOM',
                'abbreviation' => 'GB'
            ],
            [
                'country_name' => 'VENEZUELA - BOLIVARIAN REPUBLIC OF',
                'abbreviation' => 'VE'
            ],
            [
                'country_name' => 'VIET NAM',
                'abbreviation' => 'VN'
            ],
            [
                'country_name' => 'AFGHANISTAN',
                'abbreviation' => 'AF'
            ],
            [
                'country_name' => 'ÅLAND ISLANDS',
                'abbreviation' => 'AX'
            ],
            [
                'country_name' => 'ALBANIA',
                'abbreviation' => 'AL'
            ],
            [
                'country_name' => 'ALGERIA',
                'abbreviation' => 'DZ'
            ],
            [
                'country_name' => 'AMERICAN SAMOA',
                'abbreviation' => 'AS'
            ],
            [
                'country_name' => 'ANDORRA',
                'abbreviation' => 'AD'
            ],
            [
                'country_name' => 'ANGOLA',
                'abbreviation' => 'AO'
            ],
            [
                'country_name' => 'ANGUILLA',
                'abbreviation' => 'AI'
            ],
            [
                'country_name' => 'ANTARCTICA',
                'abbreviation' => 'AQ'
            ],
            [
                'country_name' => 'ANTIGUA AND BARBUDA',
                'abbreviation' => 'AG'
            ],
            [
                'country_name' => 'ARMENIA',
                'abbreviation' => 'AM'
            ],
            [
                'country_name' => 'ARUBA',
                'abbreviation' => 'AW'
            ],
            [
                'country_name' => 'AUSTRIA',
                'abbreviation' => 'AT'
            ],
            [
                'country_name' => 'AZERBAIJAN',
                'abbreviation' => 'AZ'
            ],
            [
                'country_name' => 'BAHRAIN',
                'abbreviation' => 'BH'
            ],
            [
                'country_name' => 'BANGLADESH',
                'abbreviation' => 'BD'
            ],
            [
                'country_name' => 'BARBADOS',
                'abbreviation' => 'BB'
            ],
            [
                'country_name' => 'BELARUS',
                'abbreviation' => 'BY'
            ],
            [
                'country_name' => 'BELIZE',
                'abbreviation' => 'BZ'
            ],
            [
                'country_name' => 'BENIN',
                'abbreviation' => 'BJ'
            ],
            [
                'country_name' => 'BERMUDA',
                'abbreviation' => 'BM'
            ],
            [
                'country_name' => 'BHUTAN',
                'abbreviation' => 'BT'
            ],
            [
                'country_name' => 'BOLIVIA - PLURINATIONAL STATE OF',
                'abbreviation' => 'BO'
            ],
            [
                'country_name' => 'BONAIRE - SINT EUSTATIUS AND SABA',
                'abbreviation' => 'BQ'
            ],
            [
                'country_name' => 'BOSNIA AND HERZEGOVINA',
                'abbreviation' => 'BA'
            ],
            [
                'country_name' => 'BOTSWANA',
                'abbreviation' => 'BW'
            ],
            [
                'country_name' => 'BOUVET ISLAND',
                'abbreviation' => 'BV'
            ],
            [
                'country_name' => 'BRITISH INDIAN OCEAN TERRITORY',
                'abbreviation' => 'IO'
            ],
            [
                'country_name' => 'BRUNEI DARUSSALAM',
                'abbreviation' => 'BN'
            ],
            [
                'country_name' => 'BULGARIA',
                'abbreviation' => 'BG'
            ],
            [
                'country_name' => 'BURKINA FASO',
                'abbreviation' => 'BF'
            ],
            [
                'country_name' => 'BURUNDI',
                'abbreviation' => 'BI'
            ],
            [
                'country_name' => 'CAMBODIA',
                'abbreviation' => 'KH'
            ],
            [
                'country_name' => 'CAMEROON',
                'abbreviation' => 'CM'
            ],
            [
                'country_name' => 'CAPE VERDE ISLANDS',
                'abbreviation' => 'CV'
            ],
            [
                'country_name' => 'CAYMAN ISLANDS',
                'abbreviation' => 'KY'
            ],
            [
                'country_name' => 'CENTRAL AFRICAN REPUBLIC',
                'abbreviation' => 'CF'
            ],
            [
                'country_name' => 'CHAD',
                'abbreviation' => 'TD'
            ],
            [
                'country_name' => 'CHILE',
                'abbreviation' => 'CL'
            ],
            [
                'country_name' => 'CHRISTMAS ISLAND',
                'abbreviation' => 'CX'
            ],
            [
                'country_name' => 'COCOS (KEELING) ISLANDS',
                'abbreviation' => 'CC'
            ],
            [
                'country_name' => 'COMOROS',
                'abbreviation' => 'KM'
            ],
            [
                'country_name' => 'CONGO',
                'abbreviation' => 'CG'
            ],
            [
                'country_name' => 'CONGO - THE DEMOCRATIC REPUBLIC OF THE',
                'abbreviation' => 'CD'
            ],
            [
                'country_name' => 'COOK ISLANDS',
                'abbreviation' => 'CK'
            ],
            [
                'country_name' => 'COSTA RICA',
                'abbreviation' => 'CR'
            ],
            [
                'country_name' => "CÔTE D'IVOIRE",
                'abbreviation' => 'CI'
            ],
            [
                'country_name' => 'CROATIA',
                'abbreviation' => 'HR'
            ],
            [
                'country_name' => 'CURAÇAO',
                'abbreviation' => 'CW'
            ],
            [
                'country_name' => 'CYPRUS',
                'abbreviation' => 'CY'
            ],
            [
                'country_name' => 'CZECH REPUBLIC',
                'abbreviation' => 'CZ'
            ],
            [
                'country_name' => 'DENMARK',
                'abbreviation' => 'DK'
            ],
            [
                'country_name' => 'DJIBOUTI',
                'abbreviation' => 'DJ'
            ],
            [
                'country_name' => 'DOMINICA',
                'abbreviation' => 'DM'
            ],
            [
                'country_name' => 'EGYPT',
                'abbreviation' => 'EG'
            ],
            [
                'country_name' => 'EQUATORIAL GUINEA',
                'abbreviation' => 'GQ'
            ],
            [
                'country_name' => 'ERITREA',
                'abbreviation' => 'ER'
            ],
            [
                'country_name' => 'ESTONIA',
                'abbreviation' => 'EE'
            ],
            [
                'country_name' => 'ETHIOPIA',
                'abbreviation' => 'ET'
            ],
            [
                'country_name' => 'FALKLAND ISLANDS (MALVINAS)',
                'abbreviation' => 'FK'
            ],
            [
                'country_name' => 'FAROE ISLANDS',
                'abbreviation' => 'FO'
            ],
            [
                'country_name' => 'FIJI',
                'abbreviation' => 'FJ'
            ],
            [
                'country_name' => 'FINLAND',
                'abbreviation' => 'FI'
            ],
            [
                'country_name' => 'FRENCH GUIANA',
                'abbreviation' => 'GF'
            ],
            [
                'country_name' => 'FRENCH POLYNESIA',
                'abbreviation' => 'PF'
            ],
            [
                'country_name' => 'FRENCH SOUTHERN TERRITORIES',
                'abbreviation' => 'TF'
            ],
            [
                'country_name' => 'GABON',
                'abbreviation' => 'GA'
            ],
            [
                'country_name' => 'GAMBIA',
                'abbreviation' => 'GM'
            ],
            [
                'country_name' => 'GEORGIA',
                'abbreviation' => 'GE'
            ],
            [
                'country_name' => 'GHANA',
                'abbreviation' => 'GH'
            ],
            [
                'country_name' => 'GIBRALTAR',
                'abbreviation' => 'GI'
            ],
            [
                'country_name' => 'GREECE',
                'abbreviation' => 'GR'
            ],
            [
                'country_name' => 'GREENLAND',
                'abbreviation' => 'GL'
            ],
            [
                'country_name' => 'GRENADA',
                'abbreviation' => 'GD'
            ],
            [
                'country_name' => 'GUADELOUPE',
                'abbreviation' => 'GP'
            ],
            [
                'country_name' => 'GUAM',
                'abbreviation' => 'GU'
            ],
            [
                'country_name' => 'GUERNSEY',
                'abbreviation' => 'GG'
            ],
            [
                'country_name' => 'GUINEA',
                'abbreviation' => 'GN'
            ],
            [
                'country_name' => 'GUINEA-BISSAU',
                'abbreviation' => 'GW'
            ],
            [
                'country_name' => 'GUYANA',
                'abbreviation' => 'GY'
            ],
            [
                'country_name' => 'HEARD ISLAND AND MCDONALD ISLANDS',
                'abbreviation' => 'HM'
            ],
            [
                'country_name' => 'HOLY SEE (VATICAN CITY STATE)',
                'abbreviation' => 'VA'
            ],
            [
                'country_name' => 'HONG KONG',
                'abbreviation' => 'HK'
            ],
            [
                'country_name' => 'HUNGARY',
                'abbreviation' => 'HU'
            ],
            [
                'country_name' => 'ICELAND',
                'abbreviation' => 'IS'
            ],
            [
                'country_name' => 'INDONESIA',
                'abbreviation' => 'ID'
            ],
            [
                'country_name' => 'IRAN - ISLAMIC REPUBLIC OF',
                'abbreviation' => 'IR'
            ],
            [
                'country_name' => 'IRAQ',
                'abbreviation' => 'IQ'
            ],
            [
                'country_name' => 'ISLE OF MAN',
                'abbreviation' => 'IM'
            ],
            [
                'country_name' => 'JAMAICA',
                'abbreviation' => 'JM'
            ],
            [
                'country_name' => 'JERSEY',
                'abbreviation' => 'JE'
            ],
            [
                'country_name' => 'JORDAN',
                'abbreviation' => 'JO'
            ],
            [
                'country_name' => 'KAZAKHSTAN',
                'abbreviation' => 'KZ'
            ],
            [
                'country_name' => 'KENYA',
                'abbreviation' => 'KE'
            ],
            [
                'country_name' => 'KIRIBATI',
                'abbreviation' => 'KI'
            ],
            [
                'country_name' => "KOREA - DEMOCRATIC PEOPLE'S REPUBLIC OF",
                'abbreviation' => 'KP'
            ],
            [
                'country_name' => 'KUWAIT',
                'abbreviation' => 'KW'
            ],
            [
                'country_name' => 'KYRGYZSTAN',
                'abbreviation' => 'KG'
            ],
            [
                'country_name' => "LAO PEOPLE'S DEMOCRATIC REPUBLIC",
                'abbreviation' => 'LA'
            ],
            [
                'country_name' => 'LATVIA',
                'abbreviation' => 'LV'
            ],
            [
                'country_name' => 'LEBANON',
                'abbreviation' => 'LB'
            ],
            [
                'country_name' => 'LESOTHO',
                'abbreviation' => 'LS'
            ],
            [
                'country_name' => 'LIBERIA',
                'abbreviation' => 'LR'
            ],
            [
                'country_name' => 'LIBYA',
                'abbreviation' => 'LY'
            ],
            [
                'country_name' => 'LIECHTENSTEIN',
                'abbreviation' => 'LI'
            ],
            [
                'country_name' => 'LITHUANIA',
                'abbreviation' => 'LT'
            ],
            [
                'country_name' => 'LUXEMBOURG',
                'abbreviation' => 'LU'
            ],
            [
                'country_name' => 'MACAO',
                'abbreviation' => 'MO'
            ],
            [
                'country_name' => 'MACEDONIA - THE FORMER YUGOSLAV REPUBLIC OF',
                'abbreviation' => 'MK'
            ],
            [
                'country_name' => 'MADAGASCAR',
                'abbreviation' => 'MG'
            ],
            [
                'country_name' => 'MALAWI',
                'abbreviation' => 'MW'
            ],
            [
                'country_name' => 'MALAYSIA',
                'abbreviation' => 'MY'
            ],
            [
                'country_name' => 'MALDIVES',
                'abbreviation' => 'MV'
            ],
            [
                'country_name' => 'MALI',
                'abbreviation' => 'ML'
            ],
            [
                'country_name' => 'MALTA',
                'abbreviation' => 'MT'
            ],
            [
                'country_name' => 'MARSHALL ISLANDS',
                'abbreviation' => 'MH'
            ],
            [
                'country_name' => 'MARTINIQUE',
                'abbreviation' => 'MQ'
            ],
            [
                'country_name' => 'MAURITANIA',
                'abbreviation' => 'MR'
            ],
            [
                'country_name' => 'MAURITIUS',
                'abbreviation' => 'MU'
            ],
            [
                'country_name' => 'MAYOTTE',
                'abbreviation' => 'YT'
            ],
            [
                'country_name' => 'MICRONESIA - FEDERATED STATES OF',
                'abbreviation' => 'FM'
            ],
            [
                'country_name' => 'MOLDOVA - REPUBLIC OF',
                'abbreviation' => 'MD'
            ],
            [
                'country_name' => 'MONACO',
                'abbreviation' => 'MC'
            ],
            [
                'country_name' => 'MONGOLIA',
                'abbreviation' => 'MN'
            ],
            [
                'country_name' => 'MONTENEGRO',
                'abbreviation' => 'ME'
            ],
            [
                'country_name' => 'MONTSERRAT',
                'abbreviation' => 'MS'
            ],
            [
                'country_name' => 'MOROCCO',
                'abbreviation' => 'MA'
            ],
            [
                'country_name' => 'MOZAMBIQUE',
                'abbreviation' => 'MZ'
            ],
            [
                'country_name' => 'MYANMAR',
                'abbreviation' => 'MM'
            ],
            [
                'country_name' => 'NAMIBIA',
                'abbreviation' => 'NA'
            ],
            [
                'country_name' => 'NAURU',
                'abbreviation' => 'NR'
            ],
            [
                'country_name' => 'NEPAL',
                'abbreviation' => 'NP'
            ],
            [
                'country_name' => 'NEW CALEDONIA',
                'abbreviation' => 'NC'
            ],
            [
                'country_name' => 'NEW ZEALAND',
                'abbreviation' => 'NZ'
            ],
            [
                'country_name' => 'NICARAGUA',
                'abbreviation' => 'NI'
            ],
            [
                'country_name' => 'NIGER',
                'abbreviation' => 'NE'
            ],
            [
                'country_name' => 'NIGERIA',
                'abbreviation' => 'NG'
            ],
            [
                'country_name' => 'NIUE',
                'abbreviation' => 'NU'
            ],
            [
                'country_name' => 'NORFOLK ISLAND',
                'abbreviation' => 'NF'
            ],
            [
                'country_name' => 'NORTHERN MARIANA ISLANDS',
                'abbreviation' => 'MP'
            ],
            [
                'country_name' => 'NORWAY',
                'abbreviation' => 'NO'
            ],
            [
                'country_name' => 'OMAN',
                'abbreviation' => 'OM'
            ],
            [
                'country_name' => 'PAKISTAN',
                'abbreviation' => 'PK'
            ],
            [
                'country_name' => 'PALAU',
                'abbreviation' => 'PW'
            ],
            [
                'country_name' => 'PALESTINE - STATE OF',
                'abbreviation' => 'PS'
            ],
            [
                'country_name' => 'PANAMA',
                'abbreviation' => 'PA'
            ],
            [
                'country_name' => 'PAPUA NEW GUINEA',
                'abbreviation' => 'PG'
            ],
            [
                'country_name' => 'PARAGUAY',
                'abbreviation' => 'PY'
            ],
            [
                'country_name' => 'PERU',
                'abbreviation' => 'PE'
            ],
            [
                'country_name' => 'PITCAIRN',
                'abbreviation' => 'PN'
            ],
            [
                'country_name' => 'POLAND',
                'abbreviation' => 'PL'
            ],
            [
                'country_name' => 'PORTUGAL',
                'abbreviation' => 'PT'
            ],
            [
                'country_name' => 'PUERTO RICO',
                'abbreviation' => 'PR'
            ],
            [
                'country_name' => 'QATAR',
                'abbreviation' => 'QA'
            ],
            [
                'country_name' => 'RÉUNION',
                'abbreviation' => 'RE'
            ],
            [
                'country_name' => 'ROMANIA',
                'abbreviation' => 'RO'
            ],
            [
                'country_name' => 'RUSSIAN FEDERATION',
                'abbreviation' => 'RU'
            ],
            [
                'country_name' => 'RWANDA',
                'abbreviation' => 'RW'
            ],
            [
                'country_name' => 'SAINT BARTHÉLEMY',
                'abbreviation' => 'BL'
            ],
            [
                'country_name' => 'SAINT HELENA - ASCENSION AND TRISTAN DA CUNHA',
                'abbreviation' => 'SH'
            ],
            [
                'country_name' => 'SAINT KITTS AND NEVIS',
                'abbreviation' => 'KN'
            ],
            [
                'country_name' => 'SAINT LUCIA',
                'abbreviation' => 'LC'
            ],
            [
                'country_name' => 'SAINT MARTIN (FRENCH PART)',
                'abbreviation' => 'MF'
            ],
            [
                'country_name' => 'SAINT PIERRE AND MIQUELON',
                'abbreviation' => 'PM'
            ],
            [
                'country_name' => 'SAINT VINCENT AND THE GRENADINES',
                'abbreviation' => 'VC'
            ],
            [
                'country_name' => 'SAMOA',
                'abbreviation' => 'WS'
            ],
            [
                'country_name' => 'SAN MARINO',
                'abbreviation' => 'SM'
            ],
            [
                'country_name' => 'SAO TOME AND PRINCIPE',
                'abbreviation' => 'ST'
            ],
            [
                'country_name' => 'SAUDI ARABIA',
                'abbreviation' => 'SA'
            ],
            [
                'country_name' => 'SENEGAL',
                'abbreviation' => 'SN'
            ],
            [
                'country_name' => 'SERBIA',
                'abbreviation' => 'RS'
            ],
            [
                'country_name' => 'SEYCHELLES',
                'abbreviation' => 'SC'
            ],
            [
                'country_name' => 'SIERRA LEONE',
                'abbreviation' => 'SL'
            ],
            [
                'country_name' => 'SINGAPORE',
                'abbreviation' => 'SG'
            ],
            [
                'country_name' => 'SINT MAARTEN (DUTCH PART)',
                'abbreviation' => 'SX'
            ],
            [
                'country_name' => 'SLOVAKIA',
                'abbreviation' => 'SK'
            ],
            [
                'country_name' => 'SLOVENIA',
                'abbreviation' => 'SI'
            ],
            [
                'country_name' => 'SOLOMON ISLANDS',
                'abbreviation' => 'SB'
            ],
            [
                'country_name' => 'SOMALIA',
                'abbreviation' => 'SO'
            ],
            [
                'country_name' => 'SOUTH AFRICA',
                'abbreviation' => 'ZA'
            ],
            [
                'country_name' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
                'abbreviation' => 'GS'
            ],
            [
                'country_name' => 'SOUTH SUDAN',
                'abbreviation' => 'SS'
            ],
            [
                'country_name' => 'SRI LANKA',
                'abbreviation' => 'LK'
            ],
            [
                'country_name' => 'SUDAN',
                'abbreviation' => 'SD'
            ],
            [
                'country_name' => 'SURINAME',
                'abbreviation' => 'SR'
            ],
            [
                'country_name' => 'SVALBARD AND JAN MAYEN',
                'abbreviation' => 'SJ'
            ],
            [
                'country_name' => 'SWAZILAND',
                'abbreviation' => 'SZ'
            ],
            [
                'country_name' => 'SYRIAN ARAB REPUBLIC',
                'abbreviation' => 'SY'
            ],
            [
                'country_name' => 'TAJIKISTAN',
                'abbreviation' => 'TJ'
            ],
            [
                'country_name' => 'TANZANIA - UNITED REPUBLIC OF',
                'abbreviation' => 'TZ'
            ],
            [
                'country_name' => 'THAILAND',
                'abbreviation' => 'TH'
            ],
            [
                'country_name' => 'TIMOR-LESTE',
                'abbreviation' => 'TL'
            ],
            [
                'country_name' => 'TOGO',
                'abbreviation' => 'TG'
            ],
            [
                'country_name' => 'TOKELAU',
                'abbreviation' => 'TK'
            ],
            [
                'country_name' => 'TONGA',
                'abbreviation' => 'TO'
            ],
            [
                'country_name' => 'TRINIDAD AND TOBAGO',
                'abbreviation' => 'TT'
            ],
            [
                'country_name' => 'TUNISIA',
                'abbreviation' => 'TN'
            ],
            [
                'country_name' => 'TURKEY',
                'abbreviation' => 'TR'
            ],
            [
                'country_name' => 'TURKMENISTAN',
                'abbreviation' => 'TM'
            ],
            [
                'country_name' => 'TURKS AND CAICOS ISLANDS',
                'abbreviation' => 'TC'
            ],
            [
                'country_name' => 'TUVALU',
                'abbreviation' => 'TV'
            ],
            [
                'country_name' => 'UGANDA',
                'abbreviation' => 'UG'
            ],
            [
                'country_name' => 'UKRAINE',
                'abbreviation' => 'UA'
            ],
            [
                'country_name' => 'UNITED ARAB EMIRATES',
                'abbreviation' => 'AE'
            ],
            [
                'country_name' => 'UNITED STATES MINOR OUTLYING ISLANDS',
                'abbreviation' => 'UM'
            ],
            [
                'country_name' => 'URUGUAY',
                'abbreviation' => 'UY'
            ],
            [
                'country_name' => 'UZBEKISTAN',
                'abbreviation' => 'UZ'
            ],
            [
                'country_name' => 'VANUATU',
                'abbreviation' => 'VU'
            ],
            [
                'country_name' => 'VIRGIN ISLANDS - BRITISH',
                'abbreviation' => 'VG'
            ],
            [
                'country_name' => 'VIRGIN ISLANDS - U.S',
                'abbreviation' => 'VI'
            ],
            [
                'country_name' => 'WALLIS AND FUTUNA',
                'abbreviation' => 'WF'
            ],
            [
                'country_name' => 'WESTERN SAHARA',
                'abbreviation' => 'EH'
            ],
            [
                'country_name' => 'YEMEN',
                'abbreviation' => 'YE'
            ],
            [
                'country_name' => 'ZAMBIA',
                'abbreviation' => 'ZM'
            ]
        ];

        DB::beginTransaction();
        try {
            DB::table('country')->insert($countries);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
