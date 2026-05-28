<?php

namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Models\DocumentActivity;
use Carbon\Carbon;


class DashboardController extends Controller
{
   public function index()
    {
        Document::checkExpired();
        $mouCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'MoU');
        })->count();

        $pksCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'PKS');
        })->count();

        $beritaAcaraCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'Berita Acara');
        })->count();


        // Dokumen otw expired
        $akanExpired = Document::with(['template','judul','pihak1','pihak2','user'])
            ->where('status', 'akan_expired')
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        // Dokumen activity
        $documentActivity = DocumentActivity::with(['document','user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        foreach ($akanExpired as $document) {
            $document->end_date = Carbon::parse($document->end_date)->format('d M Y');
        }

        // dd($recentDocuments);
    return view('pages.dashboard.ecommerce', compact('mouCount', 'pksCount', 'beritaAcaraCount', 'documentActivity','akanExpired'));
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
