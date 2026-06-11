<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Template;
use App\Models\Mitra;
use App\Models\Judul_Kerjasama;
use App\Models\DocumentActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\IOFactory;
use App\Helpers\ReplaceHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail; 
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;



class DocumentController extends Controller
{

    protected function slugToType($slug)
    {
        return match($slug) {
            'mou' => 'MoU',
            'pks' => 'PKS',
            'berita-acara' => 'Berita Acara',
            default => $slug,
        };
    }

    /**
     * Generate nomor document otomatis berdasarkan document type
     * Format: TYPE + NOMOR URUT (4 digit) + "/" + TAHUN
     * Contoh: MoU0001/2026, PKS0001/2026, BA0001/2026
     */
    private function generateDocumentNumber($templateId)
    {
        // Get document type dari template
        $template = Template::find($templateId);
        if (!$template) {
            throw new \Exception('Template tidak ditemukan');
        }

        $documentType = $template->document_type;
        $currentYear = Carbon::now()->year;

        // Dapatkan type prefix
        $typePrefix = $this->getTypePrefix($documentType);

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
    private function getTypePrefix($documentType)
    {
        return match ($documentType) {
            'MoU' => 'MoU',
            'PKS' => 'PKS',
            'Berita Acara' => 'BA',
            default => 'DOC',
        };
    }

    public function index($slug)
    {
        
        // $check = Document::with('user')->where('nomor_document', 'DOC0207/2026')->first();
        // dd($check->user->name);
        $type = $this->slugToType($slug);
        $query = Document::with(['pihak1', 'pihak2', 'user'])
            ->whereHas('template', function ($q) use ($type) {
                $q->where('document_type', $type);
            });

        if (auth()->check() && auth()->user()->role === 'staff') {
            $query->where('status', 'published');
        }

        $documents = $query->latest()->paginate(10);
        $documents_file = $documents->first();
        $pihak1 = $documents_file?->pihak1?->name ?? '—';
        $pihak2 = $documents_file?->pihak2?->name ?? '—';
        // $user = optional($documents_file)->user;
        return view('documents.index', compact('documents', 'type', 'slug','documents_file', 'pihak1', 'pihak2'));
    }


    public function userDocument($slug)
    {
       $type = $this->slugToType($slug);

        $query = Document::with(['pihak1', 'pihak2','user'])->where('user_id', auth()->id())
        ->whereHas('template', function ($q) use ($type) {
             $q->where('document_type', $type);
        });
    

        $documents = $query->latest()->paginate(10);
        $documents_file = $documents->first();
        $pihak1 = optional($documents_file)->pihak1 ?? '—';
        $pihak2 = optional($documents_file)->pihak2 ?? '—';
        return view('documents.user-index', compact('documents', 'type', 'slug','documents_file', 'pihak1', 'pihak2'));
    }


    public function create($slug)
    {
        $type = $this->slugToType($slug);

        // Templates matching the document type
        $templates = Template::where('document_type', $type)->get();
        $mitras = Mitra::select('id','nama')->get();
        $juduls = Judul_Kerjasama::with('mitra')->select('id','judul','mitra_id')->get();
        
        $numbers = Document::pluck('nomor_document')
        ->map(function ($item) {
            preg_match('/(\d+)$/', $item, $m);
            return (int) $m[1];
        })
        ->toArray();

        return view('documents.create', compact('templates', 'mitras', 'juduls', 'type', 'slug', 'numbers'));
    }

    public function store(Request $request)
    {
        $year = date('Y');
        $month = date('m');

        // nomor document format: DOC/2026/07/001
        $rows = DB::table('documents')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->pluck('nomor_document');

        $numbers = $rows->map(function ($item) {
            if (preg_match('/(\d+)$/', $item, $m)) {
                return (int) $m[1];
            }
            return null;
        })->filter()->sort()->values()->toArray();

        $nextNumber = 1;

        for ($i = 1; $i <= count($numbers) + 1; $i++) {
            if (!in_array($i, $numbers)) {
                $nextNumber = $i;
                break;
                }
        }

        // mode input form atau upload
        $mode = $request->input('mode', 'form');

        // Validasi
        $data = $request->validate([
            'mode' => 'required|in:form,upload',
            'template_id' => 'nullable|exists:templates,id',
            'judul_id' => 'nullable|exists:judul_kerjasamas,id',
            'status' => 'required|in:denied,draft,submitted,approved,published',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'pihak_1_id' => 'nullable|exists:mitras,id',
            'pihak_2_id' => 'nullable|exists:mitras,id',
        ]);

        
        $template = Template::findOrFail($request->template_id);
        $documentType = $template->document_type;
        $exists = Document::where('judul_id', $request->judul_id)
            ->whereHas('template', function ($q) use ($documentType) {
                $q->where('document_type', $documentType);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'judul_id' => 'Dokumen jenis ini sudah ada untuk judul kerjasama yang dipilih.'
            ]);
        }

        // Validasi berdasarkan mode
        if ($mode === 'form') {
            $request->validate([
                'content_html' => 'required|string',
            ]);
            $data['content_html'] = $request->input('content_html');
            $data['file_path'] = null;
            $data['source'] = 'generate';
        } elseif ($mode === 'upload') {
            $request->validate([
                'pdf_file' => 'required|file|mimes:pdf|max:10240', // max 10MB
            ]);

            // Simpan file ke storage
            $file = $request->file('pdf_file');
            $filePath = $file->store('documents', 'public');

            $data['file_path'] = $filePath;
            $data['content_html'] = null;
            $data['source'] = 'upload';
        }

        $nomorDocument = 'DOC/' . $year . '/' . $month . '/' .
        str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $data['user_id'] = Auth::id();
        $data['nomor_document'] = $request->input('nomor_document') ?? $nomorDocument;
        $document = Document::create($data);
        DocumentActivity::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'activity_type' => 'created',
            'description' => 'Document created with nomor ' . $document->nomor_document . '.',
        ]);

        $slug = $request->input('origin_slug');
        if (!empty($data['template_id'])) {
            $tpl = Template::find($data['template_id']);
            if ($tpl) $slug = $this->typeToSlug($tpl->document_type);
        }
    
        Alert::success('Berhasil', 'Document berhasil dibuat.');
        return redirect()->route('documents.' . ($slug ?? 'mou'));
    }

    protected function typeToSlug($type)
    {
        return match($type) {
            'MoU' => 'mou',
            'PKS' => 'pks',
            'Berita Acara' => 'berita-acara',
            default => str_replace(' ', '-', strtolower($type)),
        };
    }

    public function show(string $id)
    {
        $document = Document::findOrFail($id);

        if (auth()->check() && auth()->user()->role === 'staff' && $document->user_id !== auth()->id()) {
            abort(403);
        }

        return view('documents.show', compact('document'));
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);

        if (auth()->check() && auth()->user()->role === 'staff' && $document->user_id !== auth()->id()) {
            Alert::error('Gagal', 'Anda tidak memiliki izin untuk mengedit dokumen ini.');
            return redirect()->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'MoU'));
        }

        $docType = optional($document->template)->document_type ?? 'MoU';
        $templates = Template::where('document_type', $docType)->get();
        $mitras = Mitra::select('id','nama')->get();
        $juduls = Judul_Kerjasama::select('id','judul')->get();

        return view('documents.edit', compact('document', 'templates', 'mitras', 'juduls'));
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        if (auth()->check() && auth()->user()->role === 'staff' && $document->user_id !== auth()->id()) {
            Alert::error('Gagal', 'Anda tidak memiliki izin untuk mengedit dokumen ini.');
            return redirect()->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'MoU'));
        }

        $template = Template::findOrFail($request->template_id);
        $documentType = $template->document_type;
        $exists = Document::where('judul_id', $request->judul_id)
            ->whereHas('template', function ($q) use ($documentType) {
                $q->where('document_type', $documentType);
            })
            ->exists();
        // dd($exists);

        if ($exists) {
            return back()->withErrors([
                'judul_id' => 'Dokumen jenis ini sudah ada untuk judul kerjasama yang dipilih.'
            ]);
        }

        $mode = $request->input('mode', 'form');

        // Validasi common fields
        $data = $request->validate([
            'template_id' => 'nullable|exists:templates,id',
            'judul_id' => 'nullable|exists:judul_kerjasamas,id',
            'status' => 'required|in:denied,draft,submitted,approved,published',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'pihak_1_id' => 'nullable|exists:mitras,id',
            'pihak_2_id' => 'nullable|exists:mitras,id',
        ]);

        // Validasi berdasarkan mode
        if ($mode === 'form') {
            $request->validate([
                'content_html' => 'required|string',
            ]);
            $data['content_html'] = $request->input('content_html');
            // Hanya update file_path jika ada file baru, otherwise keep lama
            if (!$request->hasFile('pdf_file')) {
                // Keep file lama, jangan ubah
                unset($data['file_path']);
            }
            $data['source'] = 'generate';
        } elseif ($mode === 'upload') {
            if ($request->hasFile('pdf_file')) {
                $request->validate([
                    'pdf_file' => 'required|file|mimes:pdf|max:10240', // max 10MB
                ]);

                // Hapus file lama jika ada
                if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                    Storage::disk('public')->delete($document->file_path);
                }

                // Simpan file baru
                $file = $request->file('pdf_file');
                $filePath = $file->store('documents', 'public');
                $data['file_path'] = $filePath;
            } else {
                // Jika tidak ada file baru, keep file lama
                unset($data['file_path']);
            }
            
            $data['content_html'] = null;
            $data['source'] = 'upload';
        }

        DocumentActivity::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'activity_type' => 'updated',
            'description' => 'Document updated with nomor ' . $document->nomor_document . '.',
        ]);

        $document->update($data);
        Alert::success('Berhasil', 'Dokumen berhasil diperbarui.');
        return redirect()->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'MoU'));
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if (auth()->check() && auth()->user()->role === 'staff' && $document->user_id !== auth()->id()) {
            Alert::error('Gagal', 'Anda tidak memiliki izin untuk menghapus dokumen ini.');
            return redirect()->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'MoU'));
        }

        DocumentActivity::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'activity_type' => 'deleted',
            'description' => 'Document deleted with nomor ' . $document->nomor_document . '.',
        ]);

        $document->delete();
        Alert::success('Berhasil', 'Dokumen berhasil dihapus.');
        return redirect()->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'MoU'));
    }

    /**
     * @param  string  $id
     */
    public function pdf($id)
    {
        if (auth()->check() && auth()->user()->role === 'staff' && $document->user_id !== auth()->id()) {
            abort(403);
        }

        $document = Document::with(['judul','template','user','pihak1','pihak2'])->findOrFail($id);
        // dd( $document->pihak1->no_telp);
         $template = $document->content_html;

        //replace placeholder
        $htmlTemplate = ReplaceHelper::parse($template, $document);
     
        // parsing img
       $images = collect([
            'coverAtas'  => public_path('images/asset_dokumen/cover_atas.png'),
            'coverBawah' => public_path('images/asset_dokumen/cover_bawah.png'),
            'atas'       => public_path('images/asset_dokumen/atas.png'),
            'bawah'      => public_path('images/asset_dokumen/bawah.png'),
            'samping'    => public_path('images/asset_dokumen/samping.png'),
            'logoPihak1' => $document->pihak1?->tanda_tangan
                ? storage_path('app/public/' . $document->pihak1->tanda_tangan)
                : storage_path('app/public/mitra_assets/logo/bg-transparant.png'),
            'logoPihak2' => $document->pihak2?->tanda_tangan
                ? storage_path('app/public/' . $document->pihak2->tanda_tangan)
                : storage_path('app/public/mitra_assets/logo/bg-transparant.png'),
        ])->map(function ($path) {
            if (!$path || !file_exists($path)) {
                return null;
            }

            return 'data:image/png;base64,' . base64_encode(file_get_contents($path));
        });


        // Jika sumber adalah upload, stream file dari storage
        if ($document->source === 'upload' && $document->file_path) {
            $storagePath = storage_path('app/public/' . $document->file_path);
            if (file_exists($storagePath)) {
                return response()->file($storagePath);
            }
            abort(404, 'File tidak ditemukan');
        }

        // dd($htmlTemplate);
        // Jika sumber adalah generate, generate PDF dari content_html
        if ($document->source === 'generate' && $document->content_html) {
        $html = view('documents.pdf', compact('document','images','htmlTemplate'))->render();

            if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

                $filename = (optional($document->judul)->judul ?? 'document') . '.pdf';
                return $pdf->stream($filename);
            }

            // Fallback: use DOMPDF directly
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->loadHtml($html);
            $dompdf->render();

            return response($dompdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . (optional($document->judul)->judul ?? 'document') . '.pdf"'
            ]);
        }

        abort(400, 'Tidak ada konten atau file untuk ditampilkan');
    }





    // Pengajuan Dokumen
    public function PengajuanDokumen()
    {
        $documents = Document::with(['judul', 'template'])
            ->where('status', 'submitted')
            ->when(auth()->check() && auth()->user()->role === 'staff', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return view('documents.pengajuan', compact('documents'));
    }


    
    function StatusDokumen(request $request,$id)
    {
        $document = Document::findOrFail($id);

        if (auth()->check() && auth()->user()->role === 'staff') {
            $request->validate([
                'status' => 'required|in:draft,submitted',
            ]);

            $document->status = $request->input('status');
            $document->save();

            $statusLabel = $request->input('status') === 'submitted' ? 'Submitted' : 'Draft';
            DocumentActivity::create([
                'document_id' => $document->id,
                'user_id' => auth()->id(),
                'activity_type' => 'updated',
                'description' => 'Document status changed to ' . $statusLabel . '.',
            ]);

            Alert::success('Berhasil', 'Status dokumen berhasil diperbarui.');
            return redirect()->route('documents.pengajuan-dokumen');

        } elseif (auth()->check() && auth()->user()->role === 'admin') {
            $request->validate([
                'status' => 'required|in:denied,draft,approved,published',
            ]);

            $document->status = $request->input('status');
            $document->save();

            if ($request->input('status') === 'approved') {
                DocumentActivity::create([
                    'document_id' => $document->id,
                    'user_id' => auth()->id(),
                    'activity_type' => 'approved',
                    'description' => 'Document approved with nomor ' . $document->nomor_document . '.',
                ]);
            } elseif ($request->input('status') === 'denied') {
                DocumentActivity::create([
                    'document_id' => $document->id,
                    'user_id' => auth()->id(),
                    'activity_type' => 'denied',
                    'description' => 'Document denied with nomor ' . $document->nomor_document . '.',
                ]);
            }
            Alert::success('Berhasil', 'Status dokumen berhasil diperbarui.');
            return redirect()->route('documents.pengajuan-dokumen');
        }

        return view('documents.status', compact('document'));
        
    }


    // kirim email
   public function sendEmail($id)
    {
        $document = Document::with([
            'judul',
            'template',
            'user',
            'pihak1',
            'pihak2'
        ])->findOrFail($id);

        // Authorization
        if (
            auth()->check() &&
            auth()->user()->role === 'staff' &&
            $document->user_id !== auth()->id()
        ) {
            abort(403);
        }

        // Ambil recipient
        $recipient = optional($document->pihak2)->email
            ?? optional($document->user)->email
            ?? config('mail.from.address');

        if (!$recipient) {
            Alert::Error('Gagal', 'Dokumen gagal dikirimkan.');

            return redirect()
                ->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'mou'));
        }

        try {
            // kirim email
            Mail::to($recipient)
                ->send(new SendEmail($document));
            $document->status = 'published';
            $document->save();

            DocumentActivity::create([
                    'document_id' => $document->id,
                    'user_id' => auth()->id(),
                    'activity_type' => 'published',
                    'description' => 'Document published and email sent to ' . $recipient
            ]);
            
            Alert::success('Berhasil', 'Dokumen berhasil dikirimkan.');
            return redirect()
                ->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'mou'));
              

        } catch (\Exception $e) {

            \Log::error(
                'Gagal mengirim email document ID ' .
                $document->id .
                ': ' . $e->getMessage()
            );

            Alert::error('Gagal', 'Pengiriman email gagal: ' . $e->getMessage());
            return redirect()
                ->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'mou'));

        }
    }
}
