<?php

namespace App\Http\Controllers;
use App\Models\Document;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
   public function index()
    {
    // total per tipe dokumen
        $mouCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'MoU');
        })->count();

        $pksCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'PKS');
        })->count();

        $beritaAcaraCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'Berita Acara');
        })->count();

        // Dokumen terbaru
        $recentDocuments = Document::with(['template', 'judul','pihak1'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // dd($recentDocuments);
    return view('pages.dashboard.ecommerce', compact('mouCount', 'pksCount', 'beritaAcaraCount', 'recentDocuments'));
    }

    public function total(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $mouCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'MoU');
        })
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->count();

        $pksCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'PKS');
        })
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->count();

        $beritaAcaraCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'Berita Acara');
        })
        ->whereDate('created_at', '>=', $request->start_date)
        ->whereDate('created_at', '<=', $request->end_date)
        ->count();

        return response()->json([
            'mou' => $mouCount,
            'pks' => $pksCount,
            'berita_acara' => $beritaAcaraCount,
        ]);

    }
}
