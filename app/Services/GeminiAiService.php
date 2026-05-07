<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $setting = Setting::first();
        $this->apiKey = $setting->gemini_api_key ?? config('services.gemini.key') ?? env('GEMINI_API_KEY');
    }

    /**
     * Generate questions from provided text.
     *
     * @param string $text The source material
     * @param string $type Question type (pilihan_ganda or essay)
     * @param int $count Number of questions to generate
     * @return array
     */
    public function generateQuestions(string $text, string $type = 'pilihan_ganda', int $count = 5)
    {
        if (!$this->apiKey) {
            throw new \Exception('Gemini API Key not configured. Please add GEMINI_API_KEY to your .env file.');
        }

        $prompt = $this->buildPrompt($text, $type, $count);

        try {
            $response = Http::post("{$this->apiUrl}?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                throw new \Exception('AI Generation failed: ' . ($response->json('error.message') ?? 'Unknown error'));
            }

            $result = $response->json();
            $content = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            return json_decode($content, true);

        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Build the AI prompt.
     */
    protected function buildPrompt(string $text, string $type, int $count): string
    {
        $format = $type === 'pilihan_ganda' 
            ? '[{"question_text": "...", "options": [{"text": "...", "is_correct": true}, {"text": "...", "is_correct": false}, ...], "score_weight": 1}]'
            : '[{"question_text": "...", "answer_key": "...", "score_weight": 5}]';

        $typeLabel = $type === 'pilihan_ganda' ? 'Pilihan Ganda (4 opsi)' : 'Essay/Uraian';

        return "Sebagai ahli pembuat soal ujian sekolah (MTs), buatkan {$count} soal {$typeLabel} berdasarkan teks berikut ini:

---
{$text}
---

Instruksi Khusus:
1. Kembalikan data dalam format JSON murni.
2. Gunakan Bahasa Indonesia yang formal dan mudah dimengerti siswa MTs (Madrasah Tsanawiyah).
3. Untuk Pilihan Ganda, pastikan hanya ada satu jawaban yang benar.
4. Pastikan soal relevan dengan isi teks.
5. Format JSON harus mengikuti struktur ini: {$format}";
    }
}
