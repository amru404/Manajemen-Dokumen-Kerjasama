<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use App\Models\Judul_Kerjasama as Kerjasama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mitra = Mitra::select('id','nama','penanggung_jawab','jabatan','alamat','no_telp','email','logo','tanda_tangan',)
        ->get();

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
        // dd($request->all());

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string|max:50',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'tanda_tangan' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('mitra_assets/logo', 'public');
        }

        if ($request->hasFile('tanda_tangan')) {
            $validated['tanda_tangan'] = $request->file('tanda_tangan')->store('mitra_assets/tanda_tangan', 'public');
        }

        Mitra::create($validated);

        Alert::success('Berhasil', 'Data mitra berhasil disimpan.');
        return redirect()->route('mitra');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $mitra = Mitra::findOrFail($id);
        $logoUrl = $mitra->logo ? Storage::url($mitra->logo) : null;
        $tandaTanganUrl = $mitra->tanda_tangan ? $mitra->tanda_tangan : null;

        $kerjasama = Kerjasama::where('mitra_id', $mitra->id)->get();
        return view('mitra.show', compact('mitra', 'logoUrl', 'tandaTanganUrl', 'kerjasama'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mitra $mitra)
    {
        $mitra = Mitra::findOrFail($mitra->id);
        return view('mitra.edit', compact('mitra'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mitra $mitra)
    {
        $request->validate([
        'nama' => 'required|string|max:255',
        'penanggung_jawab' => 'required|string|max:255',
        'jabatan' => 'nullable|string|max:255',
        'alamat' => 'nullable|string',
        'no_telp' => 'nullable|string|max:50',
        'email' => 'required|email|max:255',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        'tanda_tangan' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
    ]);

    $data = [
        'nama' => $request->nama,
        'penanggung_jawab' => $request->penanggung_jawab,
        'jabatan' => $request->jabatan,
        'alamat' => $request->alamat,
        'no_telp' => $request->no_telp,
        'email' => $request->email,
    ];

    
    if ($request->hasFile('logo')) {
        if ($mitra->logo) {
            Storage::disk('public')->delete($mitra->logo);
        }

        $data['logo'] = $request->file('logo')->store('mitra_assets/logo', 'public');
    }

    
    if ($request->hasFile('tanda_tangan')) {
        if ($mitra->tanda_tangan) {
            Storage::disk('public')->delete($mitra->tanda_tangan);
        }

        $data['tanda_tangan'] = $request->file('tanda_tangan')->store('mitra_assets/tanda_tangan', 'public');
    } elseif ($request->remove_tanda_tangan == 1) {
        if ($mitra->tanda_tangan) {
            Storage::disk('public')->delete($mitra->tanda_tangan);
        }

        $mitra->tanda_tangan = null;
    }

    $mitra->update($data);
    Alert::success('Berhasil', 'Data mitra berhasil diperbarui.');
    return redirect()->route('mitra');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mitra $mitra)
    {
        $mitra->delete();
        Alert::success('Berhasil', 'Data mitra berhasil dihapus.');
        return redirect()->route('mitra');
    }

    /**
     * Display a listing of soft deleted resources.
     */
    public function trashed()
    {
        $mitra = Mitra::onlyTrashed()->select('id','nama','penanggung_jawab','jabatan','alamat','no_telp','email','logo','tanda_tangan','deleted_at')->get();

        return view('mitra.trashed', compact('mitra'));
    }

    /**
     * Restore a soft deleted resource.
     */
    public function restore($id)
    {
        $mitra = Mitra::onlyTrashed()->findOrFail($id);
        $mitra->restore();
        Alert::success('Berhasil', 'Data mitra berhasil dipulihkan.');
        return redirect()->route('mitra.trashed');
    }

    /**
     * Permanently delete a soft deleted resource.
     */
    public function forceDelete($id)
    {
        $mitra = Mitra::onlyTrashed()->findOrFail($id);

        if ($mitra->logo) {
            Storage::disk('public')->delete($mitra->logo);
        }

        if ($mitra->tanda_tangan) {
            Storage::disk('public')->delete($mitra->tanda_tangan);
        }

        $mitra->forceDelete();
        Alert::success('Berhasil', 'Data mitra berhasil dihapus permanen.');
        return redirect()->route('mitra.trashed');
    }
}
