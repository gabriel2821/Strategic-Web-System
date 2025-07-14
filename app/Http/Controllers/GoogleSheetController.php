<?php

namespace App\Http\Controllers;

use App\Models\ProgramRow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetController extends Controller
{
    public function storeData(Request $request)
    {
        try {
            // Fetch data with relationships
            $programRows = ProgramRow::with(['program.langkah.teras'])->get();

            if ($programRows->isEmpty()) {
                return response()->json(['error' => 'No data found in the database'], 404);
            }

            // Define headers
            $headers = [
                'Teras',
                'Langkah',
                'Program Name',
                'Inisiatif',
                'Peneraju Utama',
                'Tahun Mula/Siap',
                'Petunjuk Prestasi',
                'Pencapaian',
                'Status',
                'Completion (%)'
            ];

            // Prepare data rows
            $rows = $programRows->map(function ($row) {
                return [
                    optional(optional(optional($row->program)->langkah)->teras)->name ?? '-',
                    optional(optional($row->program)->langkah)->name ?? '-',
                    optional($row->program)->program_name ?? '-',
                    $row->inisiatif ?? '-',
                    $row->peneraju_utama ?? '-',
                    $row->tahun_mula_siap ?? '-',
                    $row->petunjuk_prestasi ?? '-',
                    $row->pencapaian ?? '-',
                    $row->status ?? '-',
                    $row->completion ?? '0',
                ];
            })->toArray();

            // Prepare payload
            $payload = [
                'secret' => env('GOOGLE_SHEET_SECRET', 'MySuperSecureSecret123!'),
                'headers' => $headers,
                'rows' => $rows,
            ];

            // Send data to Google Apps Script with retry mechanism
            $webAppUrl = env('GOOGLE_APPS_SCRIPT_URL', 'https://script.google.com/macros/s/AKfycbww2TL9EKX53fAtTLhmv-_32htzjo90gkZqnsN-qqNIakACnz0toaLLnRiaFhZcpi5WQw/exec');
            $maxRetries = 3;
            $attempt = 1;
            $response = null;

            while ($attempt <= $maxRetries) {
                try {
                    $response = Http::timeout(30)
                        ->withOptions(['verify' => true])
                        ->post($webAppUrl, $payload);

                    // Log the request for debugging
                    Log::info('Google Sheets API Request Attempt ' . $attempt, [
                        'url' => $webAppUrl,
                        'payload_size' => count($rows) . ' rows',
                        'response_status' => $response->status(),
                        'response_body' => $response->body(),
                        'response_headers' => $response->headers()
                    ]);

                    if ($response->successful() && str_contains($response->body(), '"status":"Success"')) {
                        return response()->json(['message' => 'Data berjaya ditulis ke Google Sheet']);
                    }

                    throw new \Exception('Gagal menulis ke Google Sheet: ' . $response->body());
                } catch (\Exception $e) {
                    Log::warning('Google Sheets API Request Failed', [
                        'attempt' => $attempt,
                        'error' => $e->getMessage()
                    ]);
                    if ($attempt === $maxRetries) {
                        throw $e;
                    }
                    $attempt++;
                    sleep(2);
                }
            }
        } catch (\Exception $e) {
            Log::error('Google Sheets API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Exception: ' . $e->getMessage()], 500);
        }
    }
}