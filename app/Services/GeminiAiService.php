<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    protected $apiUrl;
    protected $model;

    public function __construct()
    {
        $setting = Setting::first();
        $this->apiKey = $setting->gemini_api_key ?? config('services.gemini.key') ?? env('GEMINI_API_KEY');
        $this->model = $setting->gemini_model ?? 'gemini-1.5-flash';
        $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    /**
     * Generate questions from provided text.
     *
     * @param string $text The source material
     * @param string $type Question type (pilihan_ganda or essay)
     * @param int $count Number of questions to generate
     * @return array
     */
    public function generateQuestions(string $text, string $type = 'pilihan_ganda', int $count = 5, $classLevel = null)
    {
        if (!$this->apiKey) {
            throw new \Exception('Gemini API Key not configured. Please add GEMINI_API_KEY to your .env file.');
        }

        $prompt = $this->buildPrompt($text, $type, $count, $classLevel);


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
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 8192,
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                throw new \Exception('AI Generation failed: ' . ($response->json('error.message') ?? 'Unknown error'));
            }

            $result = $response->json();
            $content = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            // Clean up JSON if it contains markdown markers
            $content = preg_replace('/^```json\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);
            $content = trim($content);
            
            $data = json_decode($content, true);
            
            // Ensure we return a flat array of questions
            return isset($data['questions']) ? $data['questions'] : $data;

        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Evaluate/Grade a student's answer.
     */
    public function evaluateAnswer(string $question, string $key, string $answer, int $maxScore, $classLevel = null)
    {
        if (!$this->apiKey) {
            throw new \Exception('Gemini API Key not configured.');
        }

        $jenjang = 'Madrasah';
        if ($classLevel) {
            if ($classLevel <= 6) $jenjang = 'Madrasah Ibtidaiyah (MI)';
            elseif ($classLevel <= 9) $jenjang = 'Madrasah Tsanawiyah (MTs)';
            else $jenjang = 'Madrasah Aliyah (MA)';
        }

        $prompt = "Sebagai guru {$jenjang}, koreksilah jawaban siswa berikut ini berdasarkan kunci jawaban referensi.
        
        Pertanyaan: {$question}
        Kunci Jawaban Referensi: {$key}
        Jawaban Siswa: {$answer}
        Skor Maksimal: {$maxScore}

        Instruksi Koreksi:
        1. Berikan skor yang adil (0 sampai {$maxScore}).
        2. Jika jawaban siswa mirip secara makna meskipun kalimat berbeda, berikan skor tinggi.
        3. Jika jawaban siswa salah total atau tidak relevan, berikan skor rendah atau 0.
        4. Berikan alasan singkat/feedback dalam Bahasa Indonesia.
        5. Kembalikan data dalam format JSON: {\"score\": 0.0, \"feedback\": \"...\"}";

        try {
            $response = Http::post("{$this->apiUrl}?key={$this->apiKey}", [
                'contents' => [
                    ['parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                    'temperature' => 0.2, // Low temperature for more consistent grading
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception('AI Grading failed: ' . $response->body());
            }

            $result = $response->json();
            $content = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
            
            // Clean up JSON
            $content = preg_replace('/^```json\s*/', '', $content);
            $content = preg_replace('/\s*```$/', '', $content);
            
            return json_decode($content, true);

        } catch (\Exception $e) {
            Log::error('Gemini Grading Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Build the AI prompt.
     */
    protected function buildPrompt(string $text, string $type, int $count, $classLevel = null): string
    {
        $format = $type === 'pilihan_ganda' 
            ? '[{"question_text": "...", "options": [{"text": "...", "is_correct": true}, {"text": "...", "is_correct": false}, ...], "score_weight": 1}]'
            : '[{"question_text": "...", "answer_key": "...", "score_weight": 5}]';

        $jenjang = 'Madrasah';
        if ($classLevel) {
            if ($classLevel <= 6) $jenjang = 'Madrasah Ibtidaiyah (MI)';
            elseif ($classLevel <= 9) $jenjang = 'Madrasah Tsanawiyah (MTs)';
            else $jenjang = 'Madrasah Aliyah (MA)';
        }

        $typeLabel = $type === 'pilihan_ganda' ? 'Pilihan Ganda (4 opsi)' : 'Essay/Uraian';

        return "Sebagai ahli pembuat soal ujian sekolah ({$jenjang}), buatkan {$count} soal {$typeLabel} berdasarkan teks berikut ini:

---
{$text}
---

Instruksi Khusus:
1. Kembalikan data dalam format JSON murni.
2. Gunakan Bahasa Indonesia yang formal dan mudah dimengerti siswa tingkat {$jenjang} kelas {$classLevel}.
3. Untuk Pilihan Ganda, pastikan hanya ada satu jawaban yang benar.
4. Pastikan soal relevan dengan isi teks.
5. Format JSON harus mengikuti struktur ini: {$format}";
    }
    public function getCompletion(string $prompt, string $systemRole = "Anda adalah asisten AI pendidikan yang ahli dalam analisis kurikulum dan pedagogi.")
    {
        try {
            $response = Http::post("{$this->apiUrl}?key={$this->apiKey}", [
                'contents' => [
                    ['parts' => [['text' => "System Role: {$systemRole}\n\nUser Prompt: {$prompt} "]]]
                ],
                'generationConfig' => [
                    'temperature' => 0.5,
                ]
            ]);

            if ($response->failed()) {
                throw new \Exception('Gemini AI request failed: ' . $response->body());
            }

            $result = $response->json();
            return $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from AI.';
        } catch (\Exception $e) {
            Log::error('Gemini getCompletion error: ' . $e->getMessage());
            throw $e;
        }
    }
}
