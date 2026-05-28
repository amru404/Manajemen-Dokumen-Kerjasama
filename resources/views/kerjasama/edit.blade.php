@extends('/layouts.app')


@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Judul Kerjasama"/>
    <div class="space-y-6 md:space-y-7 mt-6">
        <x-common.component-card title="">
            <form method="POST" action="{{ route('judul-kerjasama.update', $judul_kerjasama) }}">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Judul Dokumen</label>
                    <input type="text" name="judul" value="{{ old('judul', $judul_kerjasama->judul) }}" required
                        class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800" />
                    @error('judul')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pilih Mitra</label>
                    <select name="mitra_id" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
                        <option value="">-- Pilih Mitra --</option>
                        @foreach($mitras as $id => $nama)
                            <option value="{{ $id }}" {{ old('mitra_id', $judul_kerjasama->mitra_id) == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    @error('mitra_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-6 flex gap-2">
                    <button type="submit" class="bg-indigo-600 text-white rounded-lg px-4 py-2">Simpan</button>
                    <a href="{{ route('judul-kerjasama') }}" class="text-sm px-4 py-2 rounded-lg border">Batal</a>
                </div>
            </form>
        </x-common.component-card>
    </div>
@endsection