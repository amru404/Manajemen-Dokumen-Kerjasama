<?php

namespace App\Helpers;

class ReplaceHelper
{
    public static function parse($template, $document)
    {
        $replacements = [
            'tanggal' => now()->format('d F Y'),

            'pihak1-nama' => $document->pihak1->nama ?? '',
            'pihak2-nama' => $document->pihak2->nama ?? '',

            'pihak1-penanggung_jawab' => $document->pihak1->penanggung_jawab ?? '',
            'pihak2-penanggung_jawab' => $document->pihak2->penanggung_jawab ?? '',

            'pihak1-jabatan' => $document->pihak1->jabatan ?? '',
            'pihak2-jabatan' => $document->pihak2->jabatan ?? '',

            'pihak1-alamat' => $document->pihak1->alamat ?? '',
            'pihak2-alamat' => $document->pihak2->alamat ?? '',

            'pihak1-telp' => $document->pihak1->no_telp ?? '',
            'pihak2-telp' => $document->pihak2->no_telp ?? '',

            'judul' => $document->judul->nama ?? '',
            'nomor' => $document->nomor_document ?? '',
        ];

        return preg_replace_callback('/{{(.*?)}}/', function ($matches) use ($replacements) {
            $key = trim($matches[1]);

            return $replacements[$key] ?? '';
        }, $template);
    }
}