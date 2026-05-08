<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::query()->updateOrCreate(
            [
                'email' => 'mi.bustanulhuda01dawuhan@gmail.com'
            ],
            [
                'email' => 'mi.bustanulhuda01dawuhan@gmail.com',
                'phone' => '0283-123456',
                'phone_hours' => 'Senin - Sabtu, 07:00 s/d 14:00',
                'owner_name' => 'Kepala Madrasah',
                'company_name' => 'MI BUSTANUL HUDA 01 DAWUHAN',
                'school_code' => 'MI-BUHUD01',
                'nsm' => '111233280040',
                'npsn' => '60713609',
                'accreditation' => 'A',
                'short_description' => 'Mewujudkan generasi yang cerdas, terampil, dan berakhlakul karimah.',
                'keyword' => 'MI, Bustanul Huda, Dawuhan, Talang, Tegal',
                'about' => 'MI Bustanul Huda 01 Dawuhan merupakan madrasah ibtidaiyah yang berdedikasi tinggi dalam mencetak generasi penerus bangsa yang unggul dalam Imtaq dan Iptek.',
                'address' => 'DESA DAWUHAN KEC. TALANG KAB. TEGAL',
                'postal_code' => '52193',
                'city' => 'TEGAL',
                'province' => 'JAWA TENGAH',
                'instagram_link' => '-',
                'twitter_link' => '-',
                'fanpage_link' => '-',
                'google_plus_link' => '-'
            ]
        );
    }
}
