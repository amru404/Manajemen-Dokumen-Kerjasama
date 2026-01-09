<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Template;
use App\Models\Mitra;
use App\Models\Judul_Kerjasama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function index($slug)
    {
        $type = $this->slugToType($slug);

        $documents = Document::whereHas('template', function ($q) use ($type) {
            $q->where('document_type', $type);
        })->latest()->paginate(10);

        return view('documents.index', compact('documents', 'type', 'slug'));
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
        return view('documents.show', compact('document'));
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        $docType = optional($document->template)->document_type ?? 'MoU';
        $templates = Template::where('document_type', $docType)->get();
        $mitras = Mitra::select('id','nama')->get();
        $juduls = Judul_Kerjasama::select('id','judul')->get();

        return view('documents.edit', compact('document', 'templates', 'mitras', 'juduls'));
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

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
