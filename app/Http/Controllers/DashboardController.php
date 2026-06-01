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
        $today = Carbon::now()->toDateString();
        
        // Total MoU Count
        $mouCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'MoU');
        })->count();
        if (auth()->check() && auth()->user()->role === 'staff') {
            $mouCount = Document::whereHas('template', function ($q) {
                $q->where('document_type', 'MoU');
            })->where('user_id', auth()->id())->count();
        }

        // Total PKS Count
        $pksCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'PKS');
        })->count();

        if (auth()->check() && auth()->user()->role === 'staff') {
            $pksCount = Document::whereHas('template', function ($q) {
                $q->where('document_type', 'PKS');
            })->where('user_id', auth()->id())->count();
        }

        // Total Berita Acara Count
        $beritaAcaraCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'Berita Acara');
        })->count();

        if (auth()->check() && auth()->user()->role === 'staff') {
            $beritaAcaraCount = Document::whereHas('template', function ($q) {
                $q->where('document_type', 'Berita Acara');
            })->where('user_id', auth()->id())->count();
        }

        // Active MoU (start_date <= today AND end_date >= today AND status = published)
        $activeMouCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'MoU');
        })
        ->where('status', 'published')
        ->where('start_date', '<=', $today)
        ->where('end_date', '>=', $today)
        ->count();

        if (auth()->check() && auth()->user()->role === 'staff') {
            $activeMouCount = Document::whereHas('template', function ($q) {
                $q->where('document_type', 'MoU');
            })
            ->where('status', 'published')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where('user_id', auth()->id())
            ->count();
        }

        // Active PKS (start_date <= today AND end_date >= today AND status = published)
        $activePksCount = Document::whereHas('template', function ($q) {
            $q->where('document_type', 'PKS');
        })
        ->where('status', 'published')
        ->where('start_date', '<=', $today)
        ->where('end_date', '>=', $today)
        ->count();

        if (auth()->check() && auth()->user()->role === 'staff') {
            $activePksCount = Document::whereHas('template', function ($q) {
                $q->where('document_type', 'PKS');
            })
            ->where('status', 'published')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where('user_id', auth()->id())
            ->count();
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

        // Count documents by status for chart
        $chartData = [];
        $statuses = ['draft', 'submitted', 'approved', 'published', 'denied', 'akan_expired', 'expired'];
        
        foreach ($statuses as $status) {
            if (auth()->check() && auth()->user()->role === 'staff') {
                $chartData[$status] = Document::where('status', $status)->where('user_id', auth()->id())->count();
            } else {
                $chartData[$status] = Document::where('status', $status)->count();
            }
        }

        // dd($recentDocuments);
    return view('pages.dashboard.index', compact('mouCount', 'pksCount', 'beritaAcaraCount', 'documentActivity','akanExpired', 'activeMouCount', 'activePksCount', 'chartData'));
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
