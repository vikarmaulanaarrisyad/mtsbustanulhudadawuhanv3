<?php

namespace Database\Seeders;

use App\Models\MailSetting;
use Illuminate\Database\Seeder;

class MailSettingSeeder extends Seeder
{
    public function run(): void
    {
        MailSetting::updateOrCreate(
            ['id' => 1],
            [
                'school_name' => 'MI BUSTANUL HUDA 01 DAWUHAN',
                'sub_header' => 'YAYASAN BUSTANUL HUDA DAWUHAN',
                'address' => 'DESA DAWUHAN KEC. TALANG KAB. TEGAL',
                'phone' => '0283-123456',
                'email' => 'mi.bustanulhuda01dawuhan@gmail.com',
                'website' => '-',
                'school_code' => 'MI.BHD.01/040/',
                'header_line_style' => 'double'
            ]
        );
    }
}
