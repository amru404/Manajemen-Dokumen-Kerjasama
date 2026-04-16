@extends('/layouts.app')

@section('content') 
<x-common.page-breadcrumb pageTitle="Edit Data Mitra"/>
 <div class="space-y-6 md:space-y-7 mt-4">
    <x-common.component-card title="">
        <form method="POST" action="{{ route('mitra.update', $mitra) }}">
            @csrf @method('PUT')

            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">Nama Mitra</label>
                <input
                    type="text"
                    name="nama"
                    value="{{ old('nama', $mitra->nama) }}"
                    required="required"
                    class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800"/>
                @error('judul')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">Penanggung Jawab</label>
                <input
                    type="text"
                    name="penanggung_jawab"
                    value="{{ old('penanggung_jawab', $mitra->penanggung_jawab) }}"
                    required="required"
                    class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800"/>
                @error('penanggung_jawab')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">Jabatan</label>
                <input
                    type="text"
                    name="jabatan"
                    value="{{ old('jabatan', $mitra->jabatan) }}"
                    required="required"
                    class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800"/>
                @error('jabatan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">Alamat</label>
                <input
                    type="text"
                    name="alamat"
                    value="{{ old('alamat', $mitra->alamat) }}"
                    required="required"
                    class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800"/>
                @error('alamat')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">No Telepon</label>
                <input
                    type="text"
                    name="no_telp"
                    value="{{ old('no_telp', $mitra->no_telp) }}"
                    required="required"
                    class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800"/>
                @error('no_telp')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label
                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400 mt-3">email</label>
                <input
                    type="text"
                    name="email"
                    value="{{ old('email', $mitra->email) }}"
                    required="required"
                    class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800"/>
                @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div
                    x-data="uploadSingleImage({
                        name: 'logo',
                        initialPreview: '{{ $mitra->logo ? asset('storage/'.$mitra->logo) : '' }}'
                    })"
                    class="border border-dashed rounded-xl p-5">
                    <label class="block text-sm font-medium mb-3">Upload Logo Mitra</label>

                    <input
                        type="file"
                        :name="name"
                        x-ref="fileInput"
                        class="hidden"
                        accept="image/png,image/jpeg,image/webp,image/svg+xml"
                        @change="handleFile($event.target.files[0])">

                        <div
                            @click="$refs.fileInput.click()"
                            @drop.prevent="handleDrop"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            :class="isDragging ? 'bg-gray-100 dark:bg-gray-800 border-brand-500' : 'bg-gray-50 dark:bg-gray-900'"
                            class="cursor-pointer border rounded-lg p-6 text-center transition">
                            <template x-if="!preview">
                                <div>
                                    <p class="font-semibold">Klik / Drag Logo</p>
                                    <p class="text-sm text-gray-500">PNG, JPG, WEBP, SVG</p>
                                </div>
                            </template>

                            <!-- preview -->
                            <template x-if="preview">
                                <div class="flex flex-col items-center gap-3">
                                    <img :src="preview" class="h-24 object-contain rounded">
                                        <button type="button" @click.stop="removeFile" class="text-red-500 text-sm">
                                            Hapus
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div
                            x-data="uploadSingleImage({
                        name: 'tanda_tangan',
                        initialPreview: '{{ $mitra->tanda_tangan ? asset('storage/'.$mitra->tanda_tangan) : '' }}'
                    })"
                            class="border border-dashed rounded-xl p-5">
                            <label class="block text-sm font-medium mb-3">Upload Tanda Tangan</label>

                            <input
                                type="file"
                                :name="name"
                                x-ref="fileInput"
                                class="hidden"
                                accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                @change="handleFile($event.target.files[0])">

                                <div
                                    @click="$refs.fileInput.click()"
                                    @drop.prevent="handleDrop"
                                    @dragover.prevent="isDragging = true"
                                    @dragleave.prevent="isDragging = false"
                                    :class="isDragging ? 'bg-gray-100 dark:bg-gray-800 border-brand-500' : 'bg-gray-50 dark:bg-gray-900'"
                                    class="cursor-pointer border rounded-lg p-6 text-center transition">
                                    <template x-if="!preview">
                                        <div>
                                            <p class="font-semibold">Klik / Drag TTD</p>
                                            <p class="text-sm text-gray-500">PNG, JPG, WEBP, SVG</p>
                                        </div>
                                    </template>

                                    <template x-if="preview">
                                        <div class="flex flex-col items-center gap-3">
                                            <img :src="preview" class="h-24 object-contain rounded">
                                                <button type="button" @click.stop="removeFile" class="text-red-500 text-sm">
                                                    Hapus
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-6 flex gap-2">
                                <button type="submit" class="bg-indigo-600 text-white rounded-lg px-4 py-2">Simpan</button>
                                <a
                                    href="{{ route('judul-kerjasama') }}"
                                    class="text-sm px-4 py-2 rounded-lg border">Batal</a>
                            </div>
                        </form>
                    </x-common.component-card>
                </div>

                <script>
                    function uploadSingleImage({
                        name,
                        initialPreview = ''
                    }) {
                        return {
                            name,
                            isDragging: false,
                            file: null,
                            preview: initialPreview,

                            handleDrop(e) {
                                this.isDragging = false;
                                const file = e
                                    .dataTransfer
                                    .files[0];
                                this.handleFile(file);
                            },

                            handleFile(file) {
                                if (!file) 
                                    return;
                                
                                const validTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml'];
                                if (!validTypes.includes(file.type)) {
                                    alert('Format tidak didukung');
                                    return;
                                }

                                this.file = file;
                                this.preview = URL.createObjectURL(file);

                                // set ke input (biar ke-submit)
                                const dt = new DataTransfer();
                                dt
                                    .items
                                    .add(file);
                                this.$refs.fileInput.files = dt.files;

                                // 🔥 FIX: biar bisa pilih file yg sama lagi
                                this.$refs.fileInput.value = '';
                            },

                            removeFile() {
                                this.file = null;
                                this.preview = '';
                                this.$refs.fileInput.value = '';
                            }
                        }
                    }
                </script>
                @endsection