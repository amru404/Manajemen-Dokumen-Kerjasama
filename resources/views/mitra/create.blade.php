@extends('/layouts.app')

@section('content') 
<x-common.page-breadcrumb pageTitle="Add Mitra"/>
@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="space-y-6 md:space-y-7 mt-4">
    <!-- form -->
    <x-common.component-card title="">
        <!-- Elements -->
        <form
            method="POST"
            action="{{ route('mitra.store') }}"
            enctype="multipart/form-data">
            @csrf
            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">
                    Nama Mitra
                </label>
                <input
                    type="text"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    name="nama"
                    value="{{ old('nama') }}"/>
            </div>

            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">
                    Nama Penanggung Jawab
                </label>
                <input
                    type="text"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    name="penanggung_jawab"
                    value="{{ old('penanggung_jawab') }}"/>
            </div>

            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">
                    Jabatan
                </label>
                <input
                    type="text"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    name="jabatan"
                    value="{{ old('jabatan') }}"/>
            </div>

            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">
                    No Telepon
                </label>
                <input
                    type="number"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    name="no_telp"
                    value="{{ old('no_telp') }}"/>
            </div>
            
            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">
                    Email
                </label>
                <input
                    type="email"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    name="email"
                    value="{{ old('email') }}"/>
            </div>

            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">
                    Alamat
                </label>
                <textarea
                    placeholder="Enter address..."
                    type="text"
                    rows="6"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                    name="alamat">{{ old('alamat') }}</textarea>
            </div>

           
          <div class="grid grid-cols-2 gap-4">
            <div x-data="{ file: null }" class="w-full">
                <span class="block mb-3 text-sm font-medium text-gray-700">
                    Upload Logo Mitra
                </span>

                <input
                    type="file"
                    name="logo"
                    x-ref="logo"
                    accept="image/*"
                    class="hidden"
                    @change="file = $event.target.files[0]"
                />

                <div
                    @click="$refs.logo.click()"
                    class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 transition bg-gray-50"
                >
                    <p class="text-sm text-gray-600">Klik untuk upload logo</p>
                </div>

                <template x-if="file">
                    <div class="mt-2 text-xs text-gray-600">
                        Selected: <span x-text="file.name"></span>
                        <button type="button" class="text-red-500 ml-2" @click="file = null">
                            hapus
                        </button>
                    </div>
                </template>

            </div>

            <!-- TTD -->
            <div x-data="{ file: null }" class="w-full">

                <span class="block mb-3 text-sm font-medium text-gray-700">
                    Upload Tanda Tangan
                </span>

                <input
                    type="file"
                    name="tanda_tangan"
                    x-ref="ttd"
                    accept="image/*"
                    class="hidden"
                    @change="file = $event.target.files[0]"
                />

                <div
                    @click="$refs.ttd.click()"
                    class="border-2 border-dashed rounded-xl p-6 text-center cursor-pointer hover:border-blue-500 transition bg-gray-50"
                >
                    <p class="text-sm text-gray-600">Klik untuk upload tanda tangan</p>
                </div>

                <template x-if="file">
                    <div class="mt-2 text-xs text-gray-600">
                        Selected: <span x-text="file.name"></span>
                        <button type="button" class="text-red-500 ml-2" @click="file = null">
                            hapus
                        </button>
                    </div>
                </template>

            </div>

        </div>
            <x-ui.button size="md" variant="primary" class="mt-4" type="submit">Submit</x-ui.button>
        </form>
    </x-common.component-card>

</div>
</div>
@endsection