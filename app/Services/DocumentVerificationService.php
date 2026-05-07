<?php

namespace App\Services;

use App\Models\DocumentVerification;
use App\Models\Student;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DocumentVerificationService
{
    /**
     * Generate a new verification record and return the code.
     */
    public function generate(Student $student, string $type, string $number, array $metadata = []): DocumentVerification
    {
        // Check if already exists for this document number
        $existing = DocumentVerification::where('document_type', $type)
            ->where('document_number', $number)
            ->first();

        if ($existing) {
            return $existing;
        }

        return DocumentVerification::create([
            'student_id' => $student->id,
            'document_type' => $type,
            'document_number' => $number,
            'verification_code' => $this->generateUniqueCode(),
            'metadata' => $metadata,
            'signed_by' => config('app.name'), // Default to app name or specific headmaster name
        ]);
    }

    /**
     * Get the verification URL for a code.
     */
    public function getVerificationUrl(string $code): string
    {
        return route('verify.document', $code);
    }

    /**
     * Generate QR code as SVG string.
     */
    public function generateQrCode(string $code, int $size = 100): string
    {
        $url = $this->getVerificationUrl($code);
        return QrCode::size($size)->generate($url);
    }

    /**
     * Generate a unique verification code.
     */
    protected function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (DocumentVerification::where('verification_code', $code)->exists());

        return $code;
    }
}
