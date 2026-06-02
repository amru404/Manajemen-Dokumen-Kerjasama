@extends('layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Add Judul Kerjasama"/>
    <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-700 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium dark:text-gray-200"></h3>
                <a href="{{ route('judul-kerjasama') }}" class="mb-4 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2 text-sm">Kembali</a>
            </div>
                    <form method="POST" action="{{ route('judul-kerjasama.store') }}">
                        @csrf

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Judul Dokumen</label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                            @error('judul')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-4">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Mitra</label>
                            <select name="mitra_id" required class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 pr-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                <option value=""> Pilih Mitra </option>
                                @foreach($mitras as $id => $nama)
                                    <option value="{{ $id }}" {{ old('mitra_id') == $id ? 'selected' : '' }}>{{ $nama }}</option>
                                @endforeach
                            </select>
                            @error('mitra_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-6 flex gap-2">
                            <button type="submit" class="mb-4 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2 text-sm">Simpan</button>
                            <a href="{{ route('judul-kerjasama') }}" class="mb-4 bg-gray-300 text-gray-500 hover:text-white hover:bg-gray-500 rounded-lg  p-2 text-sm">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


