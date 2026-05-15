<?php

namespace App\Services;

use App\Models\Holiday;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class HolidayService
{
    protected $apiUrl = 'https://api-hari-libur.vercel.app/api';

    public function syncHolidays($year = null)
    {
        $year = $year ?: date('Y');
        $response = Http::get($this->apiUrl, ['year' => $year]);

        if ($response->successful()) {
            $json = $response->json();
            $data = $json['data'] ?? [];
            $count = 0;

            foreach ($data as $item) {
                $date = $item['date'] ?? null;
                $name = $item['description'] ?? null;

                if ($date && $name) {
                    Holiday::updateOrCreate(
                        ['holiday_date' => $date],
                        ['name' => $name]
                    );
                    $count++;
                }
            }

            return [
                'success' => true,
                'message' => "Berhasil sinkronisasi $count hari libur untuk tahun $year.",
                'count' => $count
            ];
        }

        return [
            'success' => false,
            'message' => 'Gagal mengambil data dari API Hari Libur.'
        ];
    }
}
