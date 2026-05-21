<?php

namespace App\Http\Controllers;

use App\Models\Judul_Kerjasama as Kerjasama;
use App\Models\Mitra;
use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Template;

class JudulKerjasamaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kerjasama = Kerjasama::with(['mitra','documents.template'])
        ->latest()
        ->paginate(10);
        return view('kerjasama.index',compact('kerjasama'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mitras = Mitra::pluck('nama','id');
        return view('kerjasama.create', compact('mitras'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'mitra_id' => 'required|exists:mitras,id',
        ]);

        Kerjasama::create($validated);

        return redirect()->route('judul-kerjasama')->with('success', 'Judul kerjasama berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kerjasama $judul_kerjasama)
    {
        $documents = Document::with(['template','pihak1','pihak2'])
        ->where('judul_id', $judul_kerjasama->id)
        ->latest()
        ->paginate(10); 
        return view('kerjasama.detail', compact('judul_kerjasama', 'documents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kerjasama $judul_kerjasama)
    {
        $mitras = Mitra::pluck('nama','id');
        return view('kerjasama.edit', compact('judul_kerjasama','mitras'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kerjasama $judul_kerjasama)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'mitra_id' => 'required|exists:mitras,id',
        ]);

        $judul_kerjasama->update($validated);

        return redirect()->route('judul-kerjasama')->with('success', 'Judul kerjasama berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kerjasama $judul_kerjasama)
    {
        $judul_kerjasama->delete();
        return redirect()->route('judul-kerjasama')->with('success', 'Judul kerjasama berhasil dihapus.');
    }
}
