<?php

namespace App\Http\Controllers;

use App\Models\Quotes;
use App\Models\Setting;
use App\Services\GeminiAiService;
use App\Services\GroqAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class QuotesController extends Controller
{
    public function generateWithAi(Request $request)
    {
        try {
            $setting = Setting::first();
            $provider = $setting->ai_provider ?? 'gemini';

            if ($provider === 'groq') {
                $aiService = app(GroqAiService::class);
            } else {
                $aiService = app(GeminiAiService::class);
            }

            $prompt = "Buatkan sebuah kutipan atau kata mutiara inspiratif bertema pendidikan, motivasi belajar, akhlak mulia, atau kesuksesan yang sangat indah, menyentuh, dan penuh hikmah untuk website sekolah.
Format output harus berformat JSON mentah (jangan gunakan pembungkus markdown seperti ```json atau ```) dengan struktur persis seperti ini:
{
  \"quote\": \"isi kutipan di sini (jangan ada tag HTML, tulis saja langsung teksnya)...\",
  \"quote_by\": \"Nama Tokoh / Sumber\"
}";

            $result = $aiService->getCompletion($prompt);

            // Extract JSON block { ... } from the AI response to handle conversational wrappers
            $jsonStr = '';
            if (preg_match('/\{.*\}/s', $result, $matches)) {
                $jsonStr = trim($matches[0]);
            } else {
                $jsonStr = trim($result);
            }

            // Clean markdown wrappers if any
            $jsonStr = preg_replace('/```json\n?/', '', $jsonStr);
            $jsonStr = preg_replace('/```\n?/', '', $jsonStr);
            $jsonStr = trim($jsonStr);

            $data = json_decode($jsonStr, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($data['quote'])) {
                // Fallback: try to extract using smart regex
                $quote = '';
                $quoteBy = 'Anonim';

                if (preg_match('/"quote"\s*:\s*["“\'’]([^"“”\'’]+)["”\'’]/u', $jsonStr, $quoteMatch)) {
                    $quote = $quoteMatch[1];
                }
                if (preg_match('/"quote_by"\s*:\s*["“\'’]([^"“”\'’]+)["”\'’]/u', $jsonStr, $quoteByMatch)) {
                    $quoteBy = $quoteByMatch[1];
                }

                if ($quote) {
                    $data = [
                        'quote' => $quote,
                        'quote_by' => $quoteBy
                    ];
                } else {
                    $quoteText = strip_tags(trim($result));
                    if (strlen($quoteText) > 250) {
                        $quoteText = substr($quoteText, 0, 247) . '...';
                    }
                    $data = [
                        'quote' => $quoteText,
                        'quote_by' => 'Anonim'
                    ];
                }
            } else {
                // Strip HTML tags from AI output and clean it up
                $data['quote'] = strip_tags(trim($data['quote']));
                $data['quote_by'] = strip_tags(trim($data['quote_by'] ?? 'Anonim'));
            }

            // Truncate to 250 chars if the AI somehow generated a massive quote
            // that exceeds the DB column limit (since the DB column 'quote' is VARCHAR(255))
            if (strlen($data['quote']) > 250) {
                $data['quote'] = substr($data['quote'], 0, 247) . '...';
            }
            if (strlen($data['quote_by']) > 250) {
                $data['quote_by'] = substr($data['quote_by'], 0, 247) . '...';
            }

            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat kutipan AI: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.blog.quotes.index');
    }

    public function data()
    {
        $query = Quotes::orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('selectAll', function ($q) {
                return '
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input row-checkbox" name="selected[]" value="' . $q->id . '" data-id="' . $q->id . '">
                    </div>
                ';
            })
            ->addColumn('action', function ($q) {
                return '
            <button onclick="editForm(`' . route('quotes.show', $q->id) . '`)" class="btn btn-sm" style="background-color:#6755a5; color:#fff;" title="Edit">
                <i class="fa fa-pencil-alt"></i>
            </button>
            <button onclick="deleteData(`' . route('quotes.destroy', $q->id) . '`,`' . $q->quote . '`)" class="btn btn-sm" style="background-color:#d81b60; color:#fff;" title="Delete">
                <i class="fa fa-trash"></i>
            </button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quote' => 'required',
            'quote_by' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Simpan data baru
            $query = Quotes::create([
                'quote' => $request->quote,
                'quote_by' => $request->quote_by ?? 'Anonim',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan.',
                'data' => $query
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $query = Quotes::findOrfail($id);

        return response()->json(['data' => $query]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $query = Quotes::findOrfail($id);

        $validator = Validator::make($request->all(), [
            'quote' => 'required',
            'quote_by' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Simpan data baru
            $query->update([
                'quote' => $request->quote,
                'quote_by' => $request->quote_by ?? 'Anonim',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbaharui.',
                'data' => $query
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $query = Quotes::findOrfail($id);

        $query->delete();
        return response()->json(['message' => 'Data berhasil dihapus.']);
    }

    /**
     * Remove All resource from storage.
     */
    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['message' => 'Tidak ada data yang dipilih.'], 422);
        }

        try {
            // Setelah semua file dihapus, hapus data dari database
            Quotes::whereIn('id', $ids)->delete();

            return response()->json(['message' => count($ids) . ' data berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
