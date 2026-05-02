<?php

namespace Database\Seeders;

use App\Models\MailSetting;
use Illuminate\Database\Seeder;

class MailSettingSeeder extends Seeder
{
    public function run(): void
    {
        MailSetting::firstOrCreate(
            ['id' => 1],
            [
                'school_name' => 'MTs BUSTANUL HUDA DAWUHAN',
                'sub_header' => 'YAYASAN PENDIDIKAN ISLAM AL-HUDA',
                'address' => 'Jl. Dawuhan No. 123, Dawuhan, Kec. Kademangan, Kab. Blitar',
                'phone' => '(0342) 123456',
                'email' => 'mtsbustanulhuda@gmail.com',
                'website' => 'www.mtsbustanulhudadawuhan.sch.id',
                'header_line_style' => 'double'
            ]
        );
    }
}
