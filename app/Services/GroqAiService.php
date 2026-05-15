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
                        'content' => "Anda adalah ahli pembuat soal ujian {$jenjang}. Kembalikan hanya data dalam format JSON murni."
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7,
            ]);

            if ($response->failed()) {
                Log::error('Groq API Error: ' . $response->body());
                throw new \Exception('AI Generation (Groq) failed: ' . ($response->json('error.message') ?? 'Unknown error'));
            }

            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? '';
            $data = json_decode($content, true);
            
            return isset($data['questions']) ? $data['questions'] : $data;

        } catch (\Exception $e) {
            Log::error('Groq Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Evaluate/Grade a student's answer.
     */
    public function evaluateAnswer(string $question, string $key, string $answer, int $maxScore, $classLevel = null)
    {
        if (!$this->apiKey) {
            throw new \Exception('Groq API Key not configured.');
        }

        $jenjang = 'Madrasah';
        if ($classLevel) {
            if ($classLevel <= 6) $jenjang = 'Madrasah Ibtidaiyah (MI)';
            elseif ($classLevel <= 9) $jenjang = 'Madrasah Tsanawiyah (MTs)';
            else $jenjang = 'Madrasah Aliyah (MA)';
        }

        $prompt = "Pertanyaan: {$question}\nKunci Jawaban Referensi: {$key}\nJawaban Siswa: {$answer}\nSkor Maksimal: {$maxScore}\n\nKoreksilah jawaban siswa di atas secara adil sesuai tingkat pendidikan {$jenjang}. Kembalikan JSON: {\"score\": 0.0, \"feedback\": \"...\"}";

        try {
            $response = Http::withToken($this->apiKey)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Anda adalah guru {$jenjang} yang ahli dalam mengoreksi jawaban essay secara adil. Kembalikan data dalam format JSON murni."
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.2,
            ]);

            if ($response->failed()) {
                Log::error('Groq API Error: ' . $response->body());
                throw new \Exception('AI Grading failed: ' . ($response->json('error.message') ?? 'Unknown error'));
            }

            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? '{}';
            
            return json_decode($content, true);

        } catch (\Exception $e) {
            Log::error('Groq Grading Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Build the AI prompt.
     */
    protected function buildPrompt(string $text, string $type, int $count, $classLevel = null, $jenjang = 'Madrasah'): string
    {
        $format = $type === 'pilihan_ganda' 
            ? '{"questions": [{"question_text": "...", "options": [{"text": "...", "is_correct": true}, {"text": "...", "is_correct": false}, ...], "score_weight": 1}]}'
            : '{"questions": [{"question_text": "...", "answer_key": "...", "score_weight": 5}]}';

        $typeLabel = $type === 'pilihan_ganda' ? 'Pilihan Ganda (4 opsi)' : 'Essay/Uraian';

        return "Berdasarkan teks berikut ini, buatkan {$count} soal {$typeLabel} untuk level {$jenjang} kelas {$classLevel}:

---
{$text}
---

Instruksi:
1. Gunakan Bahasa Indonesia formal yang sesuai tingkat pemahaman siswa {$jenjang}.
2. Format output WAJIB JSON murni sesuai struktur ini: {$format}
3. Masukkan semua soal ke dalam array 'questions'.";
    }
}
