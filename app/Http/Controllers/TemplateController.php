<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class TemplateController extends Controller
{
    public function index()
    {
        // select all columns, order latest, paginate 10 (avoid ::all as requested)
        $templates = Template::select('*')->latest()->paginate(10);

        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        return view('templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'document_type' => 'required|in:MoU,PKS,Berita Acara',
            'description' => 'nullable|string',
            'content_html' => 'required|string',
        ]);

        $data['user_id'] = Auth::id();

        $template = Template::create($data);

        Alert::success('Berhasil', 'Template berhasil dibuat.');
        return redirect()->route('templates.index');
    }

    public function show(Template $template)
    {
        return view('templates.show', compact('template'));
    }

    public function edit(Template $template)
    {
        return view('templates.edit', compact('template'));
    }

    public function update(Request $request, Template $template)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'document_type' => 'required|in:MoU,PKS,Berita Acara',
            'description' => 'nullable|string',
            'content_html' => 'required|string',
        ]);

        $template->update($data);

        Alert::success('Berhasil', 'Template berhasil diperbarui.');
        return redirect()->route('templates.index');
    }

    public function destroy(Template $template)
    {
        $template->delete();
        Alert::success('Berhasil', 'Template berhasil dihapus.');
        return redirect()->route('templates.index');
    }
}
