<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqAiService
{
    protected $apiKey;
    protected $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    protected $model;

    public function __construct()
    {
        $setting = Setting::first();
        $this->apiKey = $setting->groq_api_key ?? env('GROQ_API_KEY');
        $model = $setting->groq_model ?? 'llama-3.1-8b-instant';
        
        // Auto-map decommissioned models
        $map = [
            'llama3-8b-8192' => 'llama-3.1-8b-instant',
            'llama3-70b-8192' => 'llama-3.3-70b-versatile',
        ];
        
        $this->model = $map[$model] ?? $model;
    }

    /**
     * Generate questions from provided text.
     */
    public function generateQuestions(string $text, string $type = 'pilihan_ganda', int $count = 5, $classLevel = null)
    {
        if (!$this->apiKey) {
            throw new \Exception('Groq API Key not configured. Please add GROQ_API_KEY to your settings.');
        }

        $jenjang = 'Madrasah';
        if ($classLevel) {
            if ($classLevel <= 6) $jenjang = 'Madrasah Ibtidaiyah (MI)';
            elseif ($classLevel <= 9) $jenjang = 'Madrasah Tsanawiyah (MTs)';
            else $jenjang = 'Madrasah Aliyah (MA)';
        }

        $prompt = $this->buildPrompt($text, $type, $count, $classLevel, $jenjang);

        try {
            $response = Http::withToken($this->apiKey)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Anda adalah ahli pembuat soal ujian {$jenjang}. Kembalikan hanya data dalam format JSON murni dengan root key 'questions'."
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.8,
            ]);

            if ($response->failed()) {
                Log::error('Groq API Error: ' . $response->body());
                throw new \Exception('AI Generation (Groq) failed: ' . ($response->json('error.message') ?? 'Unknown error'));
            }

            $result = $response->json();
            
            // Track total tokens consumed
            $tokens = $result['usage']['total_tokens'] ?? 0;
            if ($tokens > 0) {
                try {
                    Setting::first()->increment('groq_tokens_this_month', $tokens);
                } catch (\Exception $ex) {
                    Log::warning('Failed to increment Groq token usage: ' . $ex->getMessage());
                }
            }

            $content = $result['choices'][0]['message']['content'] ?? '';
            $data = json_decode($content, true);
            
            return isset($data['questions']) ? $data['questions'] : $data;

        } catch (\Exception $e) {
            Log::error('Groq Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    // ... evaluateAnswer stays same ...

    /**
     * Build the AI prompt.
     */
    protected function buildPrompt(string $text, string $type, int $count, $classLevel = null, $jenjang = 'Madrasah'): string
    {
        $formats = [
            'pilihan_ganda' => '{"questions": [{"question_text": "...", "options": [{"text": "...", "is_correct": true}, {"text": "...", "is_correct": false}, {"text": "...", "is_correct": false}, {"text": "...", "is_correct": false}], "score_weight": 1}]}',
            'ganda_komplek' => '{"questions": [{"question_text": "...", "options": [{"text": "...", "is_correct": true}, {"text": "...", "is_correct": true}, {"text": "...", "is_correct": false}, {"text": "...", "is_correct": false}], "score_weight": 2}]}',
            'penjodohan' => '{"questions": [{"question_text": "...", "matching_pairs": {"Premis 1": "Jawaban 1", "Premis 2": "Jawaban 2"}, "score_weight": 3}]}',
            'essay' => '{"questions": [{"question_text": "...", "answer_key": "...", "score_weight": 5}]}',
            'uraian' => '{"questions": [{"question_text": "...", "answer_key": "...", "score_weight": 10}]}',
        ];

        $labels = [
            'pilihan_ganda' => 'Pilihan Ganda (1 jawaban benar)',
            'ganda_komplek' => 'Pilihan Ganda Kompleks (Banyak jawaban benar)',
            'penjodohan' => 'Penjodohan (Pasangan)',
            'essay' => 'Essay Singkat',
            'uraian' => 'Uraian Panjang',
        ];

        $format = $formats[$type] ?? $formats['pilihan_ganda'];
        $typeLabel = $labels[$type] ?? $labels['pilihan_ganda'];

        return "Berdasarkan teks berikut ini, buatkan {$count} soal {$typeLabel} untuk level {$jenjang} kelas {$classLevel}:

---
{$text}
---

Instruksi:
1. Gunakan Bahasa Indonesia formal yang sesuai tingkat pemahaman siswa {$jenjang}.
2. Format output WAJIB JSON murni sesuai struktur ini: {$format}
3. Masukkan semua soal ke dalam array 'questions'.
4. Pastikan data valid dan relevan.";
    }
    public function getCompletion(string $prompt, string $systemRole = "Anda adalah asisten AI pendidikan yang ahli dalam analisis kurikulum dan pedagogi.")
    {
        try {
            $response = Http::withToken($this->apiKey)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemRole],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.5,
            ]);

            if ($response->failed()) {
                throw new \Exception('Groq AI request failed: ' . $response->body());
            }

            $result = $response->json();

            // Track total tokens consumed
            $tokens = $result['usage']['total_tokens'] ?? 0;
            if ($tokens > 0) {
                try {
                    Setting::first()->increment('groq_tokens_this_month', $tokens);
                } catch (\Exception $ex) {
                    Log::warning('Failed to increment Groq token usage: ' . $ex->getMessage());
                }
            }

            return $result['choices'][0]['message']['content'] ?? 'No response from AI.';
        } catch (\Exception $e) {
            Log::error('Groq getCompletion error: ' . $e->getMessage());
            throw $e;
        }
    }
    public function generateImagePrompt(string $questionText): string
    {
        $prompt = "Berdasarkan pertanyaan ujian berikut, buatkan 1 baris prompt gambar dalam Bahasa Inggris yang sangat deskriptif untuk diinputkan ke AI Image Generator (seperti DALL-E atau Stable Diffusion). 
        Fokus pada objek utama, gaya ilustrasi pendidikan yang bersih, dan latar belakang putih/netral.
        
        Pertanyaan: {$questionText}
        
        Kembalikan HANYA teks prompt dalam Bahasa Inggris.";

        return $this->getCompletion($prompt, "Anda adalah ahli desain instruksional yang mahir membuat prompt gambar untuk materi edukasi.");
    }
}
