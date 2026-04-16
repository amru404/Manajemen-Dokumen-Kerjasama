<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Template;
use App\Models\Mitra;
use App\Models\Judul_Kerjasama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $type = $this->slugToType($slug);

        $query = Document::whereHas('template', function ($q) use ($type) {
            $q->where('document_type', $type);
        });

        if (auth()->check() && auth()->user()->role === 'staff') {
            $query->where('user_id', auth()->id());
        }

        $documents = $query->latest()->paginate(10);
        $documents_file = $documents->first();

        return view('documents.index', compact('documents', 'type', 'slug','documents_file'));
    }


    public function create($slug)
    {
        $type = $this->slugToType($slug);

        // Templates matching the document type
        $templates = Template::where('document_type', $type)->get();
        $mitras = Mitra::select('id','nama')->get();
        $juduls = Judul_Kerjasama::select('id','judul')->get();

        return view('documents.create', compact('templates', 'mitras', 'juduls', 'type', 'slug'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'template_id' => 'nullable|exists:templates,id',
            'judul_id' => 'nullable|exists:judul_kerjasamas,id',
            'content_html' => 'required|string',
            'status' => 'required|in:draft,final,published',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'pihak_1_id' => 'nullable|exists:mitras,id',
            'pihak_2_id' => 'nullable|exists:mitras,id',
        ]);

        $data['user_id'] = Auth::id();

        // Generate nomor document otomatis
        if (!empty($data['template_id'])) {
            $data['nomor_document'] = $this->generateDocumentNumber($data['template_id']);
        }

        $document = Document::create($data);

        // determine redirect slug from selected template (if provided) or fallback to origin_slug
        $slug = $request->input('origin_slug');
        if (!empty($data['template_id'])) {
            $tpl = Template::find($data['template_id']);
            if ($tpl) $slug = $this->typeToSlug($tpl->document_type);
        }

        return redirect()->route('documents.' . ($slug ?? 'mou'))->with('success', 'Document created.');
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
            abort(403);
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
            abort(403);
        }

        $data = $request->validate([
            'template_id' => 'nullable|exists:templates,id',
            'judul_id' => 'nullable|exists:judul_kerjasamas,id',
            'content_html' => 'required|string',
            'status' => 'required|in:draft,final,published',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'pihak_1_id' => 'nullable|exists:mitras,id',
            'pihak_2_id' => 'nullable|exists:mitras,id',
        ]);

        $document->update($data);
        return redirect()->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'MoU'))->with('success', 'Document updated.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if (auth()->check() && auth()->user()->role === 'staff' && $document->user_id !== auth()->id()) {
            abort(403);
        }

        $document->delete();

        return redirect()->route('documents.' . $this->typeToSlug(optional($document->template)->document_type ?? 'MoU'))->with('success', 'Document deleted.');
    }

    /**
     * Generate PDF for a document using dompdf
     *
     * @param  string  $id
     */
    public function pdf($id)
    {
        $document = Document::with(['judul','template','user','pihak1','pihak2'])->findOrFail($id);

        if (auth()->check() && auth()->user()->role === 'staff' && $document->user_id !== auth()->id()) {
            abort(403);
        }

        // If using barryvdh/laravel-dompdf (preferred)
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('documents.pdf', compact('document'))
                ->setPaper('a4', 'portrait')
                ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->stream((optional($document->judul)->judul ?? 'document') . '.pdf');
        }

        // Fallback: use DOMPDF directly
        $html = view('documents.pdf', compact('document'))->render();
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->loadHtml($html);
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . (optional($document->judul)->judul ?? 'document') . '.pdf"'
        ]);
    }
}
