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
        if (auth()->check() && auth()->user()->role === 'staff') {
            $mouCount = Document::whereHas('template', function ($q) {
                $q->where('document_type', 'MoU');
            })->where('user_id', auth()->id())->count();
        }

        $pksCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'PKS');
        })->count();

        if (auth()->check() && auth()->user()->role === 'staff') {
            $pksCount = Document::whereHas('template', function ($q) {
                $q->where('document_type', 'PKS');
            })->where('user_id', auth()->id())->count();
        }

        $beritaAcaraCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'Berita Acara');
        })->count();

        if (auth()->check() && auth()->user()->role === 'staff') {
            $beritaAcaraCount = Document::whereHas('template', function ($q) {
                $q->where('document_type', 'Berita Acara');
            })->where('user_id', auth()->id())->count();
        }

        // Dokumen otw expired
        $akanExpired = Document::with(['template','judul','pihak1','pihak2','user'])
            ->where('status', 'akan_expired')
            ->orderBy('end_date', 'asc')
            ->take(5)
            ->get();

        if (auth()->check() && auth()->user()->role === 'staff') {
            $akanExpired = Document::with(['template','judul','pihak1','pihak2','user'])
                    ->where('status', 'akan_expired')
                    ->where('user_id', auth()->id())
                    ->orderBy('end_date', 'asc')
                    ->take(5)
                    ->get();
            }

        // Dokumen activity
        $documentActivity = DocumentActivity::with(['document','user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

            if (auth()->check() && auth()->user()->role === 'staff') {
                $documentActivity = DocumentActivity::with(['document','user'])->where('user_id', auth()->id())
                    ->whereHas('document', function ($q) {
                        $q->where('user_id', auth()->id());
                    })
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            }

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

         if (auth()->check() && auth()->user()->role === 'staff') {
            Alert::error('Gagal', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            return redirect()->route('documents.' . $slug);
        }

        return response()->json([
            'mou' => $mouCount,
            'pks' => $pksCount,
            'berita_acara' => $beritaAcaraCount,
        ]);

    }
}
