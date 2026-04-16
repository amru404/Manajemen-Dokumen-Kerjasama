<?php

namespace App\Services;

use App\Models\Document;
use Carbon\Carbon;

class DocumentNumberService
{
    /**
     * Generate nomor document otomatis berdasarkan document type
     * Format: TYPE + NOMOR URUT (4 digit) + "/" + TAHUN
     * Contoh: MoU0001/2026, PKS0001/2026, BA0001/2026
     */
    public static function generateDocumentNumber($documentTypeId)
    {
        // Get document type dari template
        $template = \App\Models\Template::find($documentTypeId);
        if (!$template) {
            throw new \Exception('Template tidak ditemukan');
        }

        $documentType = $template->document_type;
        $currentYear = Carbon::now()->year;

        // Dapatkan type prefix
        $typePrefix = self::getTypePrefix($documentType);

        // Hitung nomor urut terakhir untuk type ini pada tahun ini
        $lastNumber = Document::where('nomor_document', 'like', $typePrefix . '%/' . $currentYear)
            ->orderByRaw('CAST(SUBSTR(nomor_document, ' . (strlen($typePrefix) + 1) . ', 4) AS UNSIGNED) DESC')
            ->first();

        if ($lastNumber) {
            // Extract nomor dari format TYPE0001/2026
            preg_match('/(' . preg_quote($typePrefix) . ')(\d+)/', $lastNumber->nomor_document, $matches);
            $lastNum = intval($matches[2]);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1;
        }

        // Generate nomor dengan format 4 digit
        $nomorDocument = $typePrefix . str_pad($nextNum, 4, '0', STR_PAD_LEFT) . '/' . $currentYear;

        return $nomorDocument;
    }

    /**
     * Get type prefix berdasarkan document type
     */
    private static function getTypePrefix($documentType)
    {
        return match ($documentType) {
            'MoU' => 'MoU',
            'PKS' => 'PKS',
            'Berita Acara' => 'BA',
            default => 'DOC',
        };
    }

    /**
     * Validate nomor document format
     */
    public static function isValidNomorDocument($nomorDocument)
    {
        // Format: TYPE0001/2026
        return (bool) preg_match('/^[A-Za-z]{2,3}\d{4}\/\d{4}$/', $nomorDocument);
    }
}
