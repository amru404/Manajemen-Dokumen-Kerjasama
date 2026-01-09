<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Judul_Kerjasama as Kerjasama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mitra = Mitra::select('id','nama','penanggung_jawab','jabatan','alamat','no_telp','logo','tanda_tangan',)
        ->latest()
        ->paginate(10);

        return view('mitra.index',compact('mitra'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mitra.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'tanda_tangan' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('mitra/logo', 'public');
        }

        if ($request->hasFile('tanda_tangan')) {
            $validated['tanda_tangan'] = $request->file('tanda_tangan')->store('mitra/tanda_tangan', 'public');
        }

        Mitra::create($validated);

        return redirect()->route('mitra')->with('success', 'Data mitra berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mitra = Mitra::findOrFail($id);
        $logoUrl = $mitra->logo ? Storage::url($mitra->logo) : null;
        $tandaTanganUrl = $mitra->tanda_tangan ? Storage::url($mitra->tanda_tangan) : null;

        $kerjasama = Kerjasama::where('mitra_id', $mitra->id)->get();
        return view('mitra.show', compact('mitra', 'logoUrl', 'tandaTanganUrl', 'kerjasama'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mitra $mitra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mitra $mitra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mitra $mitra)
    {
        //
    }
}
