@extends('/layouts.app')

@section('content') 
<x-common.page-breadcrumb pageTitle="Create Document - {{ $type }}" />
<div class="space-y-6 mt-4">
    <div
        class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-700 dark:bg-white/[0.03]">

        <!-- Alpine.js Container -->
        <div x-data="documentForm()" class="space-y-6">

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex gap-4 -mb-px" role="tablist">
                    <button
                        @click="activeTab = 'form'"
                        :class="activeTab === 'form' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'border-b-2 border-transparent text-gray-600'"
                        class="px-4 py-2 font-medium transition-colors"
                        role="tab">
                        Form Editor
                    </button>
                    <button
                        @click="activeTab = 'upload'"
                        :class="activeTab === 'upload' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'border-b-2 border-transparent text-gray-600'"
                        class="px-4 py-2 font-medium transition-colors"
                        role="tab">
                       Upload File
                    </button>
                </nav>
            </div>

            <!-- Form Start -->
            <form
                action="{{ route('documents.store') }}"
                method="POST"
                enctype="multipart/form-data"
                @submit="onSubmit">
                @csrf
                <input type="hidden" name="origin_slug" value="{{ $slug }}">
                    <input type="hidden" name="mode" :value="activeTab">

                        <!-- Common Fields (tidak dalam tabs) -->
                        <div class="space-y-4 pb-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Dokumen</label>
                                <input
                                    type="text"
                                    name="nomor_document"
                                    class="mt-1 block w-full rounded-md border px-3 py-2"
                                    :value="nomorDocument"
                                    @change="nomorDocument = $event.target.value"
                                    required="required">
                                    @error('nomor_document')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Judul Kerjasama</label>
                                    <select name="judul_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                                        <option value=""> pilih judul </option>
                                        @foreach($juduls as $j)
                                        <option
                                            value="{{ $j->id }}"
                                            {{ (old('judul_id') == $j->id || request('judul_id') == $j->id) ? 'selected' : '' }}>
                                                {{ $j->judul }} -  {{ optional($j->mitra)->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('judul_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Template (Optional)</label>
                                    <select
                                        id="template_select"
                                        name="template_id"
                                        class="mt-1 block w-full rounded-md border px-3 py-2">
                                        <option value=""> pilih template </option>
                                        @foreach($templates as $tpl)
                                        <option value="{{ $tpl->id }}">{{ $tpl->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('template_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pihak Pertama</label>
                                        <select name="pihak_1_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                                            <option value=""> pilih pihak 1 </option>
                                            @foreach($mitras as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pihak Kedua</label>
                                        <select name="pihak_2_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                                            <option value=""> pilih pihak 2 </option>
                                            @foreach($mitras as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                        <input
                                            type="date"
                                            name="start_date"
                                            class="mt-1 block w-full rounded-md border px-3 py-2"></div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tanggal Berakhir</label>
                                            <input
                                                type="date"
                                                name="end_date"
                                                class="mt-1 block w-full rounded-md border px-3 py-2"></div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <select
                                                name="status"
                                                class="mt-1 block w-full rounded-md border px-3 py-2"
                                                required="required">
                                                @if(auth()->user() && auth()->user()->role === 'admin')
                                                <option value="denied">Denied</option>
                                                <option value="draft">Draft</option>
                                                <option value="submitted">Submitted</option>
                                                <option value="approved">Approved</option>
                                                <option value="published">Published</option>
                                                @elseif(auth()->user() && auth()->user()->role === 'staff')
                                                <option value="draft">Draft</option>
                                                <option value="submitted">Submitted</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <!-- TAB 1: Form Editor -->
                                    <div
                                        x-show="activeTab === 'form'"
                                        class="space-y-4 py-6"
                                        @click="resetUploadField()">
                                        <h3 class="text-lg font-semibold text-gray-900">Gunakan Form Editor</h3>
                                        <div class="max-h-96 overflow-y-auto border border-gray-200 rounded-lg dark:border-gray-700">
                                            <div class="p-4">
                                                <label class="block text-sm font-medium text-gray-700">Konten Dokumen</label>
                                                <textarea
                                                    id="document_content"
                                                    name="content_html"
                                                    class="mt-1 block w-full rounded-md border px-3 py-2 font-mono text-sm"
                                                    rows="15"
                                                    x-model="formContent"
                                                    @change="formContent = $event.target.value">{{ old('content_html') }}</textarea>
                                                @error('content_html')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- TAB 2: Upload File -->
                                    <div
                                        x-show="activeTab === 'upload'"
                                        class="space-y-4 py-6"
                                        @click="resetFormContent()">
                                        <h3 class="text-lg font-semibold text-gray-900">Upload Dokumen Jadi (PDF)</h3>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">File PDF</label>
                                            <div class="mt-1 relative">
                                                <input
                                                    type="file"
                                                    name="pdf_file"
                                                    id="pdf_file"
                                                    accept="application/pdf"
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                                    @change="uploadedFileName = $event.target.files[0]?.name || ''"></div>
                                                <p x-show="uploadedFileName" class="text-xs text-green-600 mt-2">✓ File:
                                                    <span x-text="uploadedFileName"></span>
                                                </p>
                                                @error('pdf_file')
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                @enderror
                                                <p class="text-xs text-gray-500 mt-1">Hanya file PDF yang diterima (max 10MB)</p>
                                            </div>
                                        </div>

                                        <!-- Submit Buttons -->
                                        <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                                            <button
                                                type="submit"
                                                class="bg-indigo-500 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                                                Save
                                            </button>
                                            <a
                                                href="#"
                                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-2 rounded-md font-medium transition-colors">
                                                Batal
                                            </a>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
                        

                        <script> 
                            function documentForm() {
                                return {
                                    activeTab: 'form',
                                    formContent: `{{ old('content_html') }}`,
                                    uploadedFileName: '',
                                    nomorDocument: '',

                                    onSubmit(e) {
                                        // Sinkronisasi: jika CKEditor aktif, ambil data darinya ke Alpine model
                                        if (window.editorInstance && typeof window.editorInstance.getData === 'function') {
                                            this.formContent = window.editorInstance.getData();
                                        }

                                        // Validasi: minimal salah satu terisi
                                        if (this.activeTab === 'form' && !this.formContent.trim()) {
                                            e.preventDefault();
                                            alert('Silakan isi konten dokumen');
                                            return;
                                        }

                                        if (this.activeTab === 'upload' && !document.getElementById('pdf_file').files.length) {
                                            e.preventDefault();
                                            alert('Silakan pilih file PDF');
                                            return;
                                        }
                                    },

                                    resetFormContent() {
                                        // Saat pindah ke tab upload, clear form content di form Tapi jangan di model,
                                        // karena kita perlu preserve untuk kembali
                                    },

                                    resetUploadField() {
                                        // Saat pindah ke tab form, clear upload field
                                        document
                                            .getElementById('pdf_file')
                                            .value = '';
                                        this.uploadedFileName = '';
                                    },

                                    init() {
                                        // Generate default nomor document
                                        const now = new Date();
                                        const year = now.getFullYear();
                                        const random = Math
                                            .floor(Math.random() * 10000)
                                            .toString()
                                            .padStart(4, '0');
                                        this.nomorDocument = `DOC${random}/${year}`;
                                    }
                                }
                            }

                            // Initialize CKEditor untuk textarea jika dibutuhkan
                            document.addEventListener('DOMContentLoaded', function () {
                                const templates = @json(
                                    $templates->mapWithKeys(fn($t) => [$t->id => $t->content_html])
                                );

                                const templateSelect = document.getElementById('template_select');
                                const contentArea = document.getElementById('document_content');

                                if (templateSelect) {
                                    templateSelect.addEventListener('change', function () {
                                        if (this.value && templates[this.value]) {
                                            const tpl = templates[this.value];
                                            // If CKEditor is initialized, update its data. Otherwise update the textarea.
                                            if (window.editorInstance && typeof window.editorInstance.setData === 'function') {
                                                window.editorInstance.setData(tpl);
                                            } else {
                                                contentArea.value = tpl;
                                                // dispatch input so Alpine x-model updates
                                                contentArea.dispatchEvent(new Event('input', {bubbles: true}));
                                            }

                                            // Also update Alpine model directly if present so validation sees it immediately
                                            const alpineRoot = document.querySelector('[x-data]');
                                            if (alpineRoot && alpineRoot.__x && alpineRoot.__x.$data) {
                                                alpineRoot.__x.$data.formContent = tpl;
                                            }
                                        }
                                    });
                                }
                            });
                        </script>

                        <style>
                            input[type="date"] {
                                @apply cursor-pointer;
                            }
                        </style>

                        <script>
                            ClassicEditor.create(document.querySelector('#document_content'), {
                                htmlSupport: {
                                    allow: [
                                        {
                                            name: /.*/,
                                            styles: true,
                                            attributes: true,
                                            classes: true
                                        }
                                    ]
                                },
                            pasteFromOffice: {
                                    keepFormatting: true
                                },
                                toolbar: {
                                    items: [
                                        'heading',

                                        '|',
                                        'bold', 'italic', 'underline', 'strikethrough',
                                        'subscript', 'superscript',

                                        '|',
                                        'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor',

                                        '|',
                                        'alignment:left', 'alignment:center', 'alignment:right', 'alignment:justify',

                                        '|',
                                        'bulletedList', 'numberedList', 'todoList',

                                        '|',
                                        'outdent', 'indent',

                                        '|',
                                        'link', 'blockQuote',

                                        '|',
                                        'insertTable',

                                        '|',
                                        'horizontalLine', 'specialCharacters',

                                        '|',
                                        'undo', 'redo'
                                    ],
                                    shouldNotGroupWhenFull: true
                                },

                                alignment: {
                                    options: ['left', 'center', 'right', 'justify']
                                },

                                fontFamily: {
                                    options: [
                                        'default',
                                        'Arial, Helvetica, sans-serif',
                                        'Times New Roman, Times, serif',
                                        'Calibri, sans-serif',
                                        'Georgia, serif',
                                        'Courier New, Courier, monospace'
                                    ],
                                    supportAllValues: true
                                },

                                fontSize: {
                                    options: [8, 9, 10, 11, 12, 14, 16, 18, 20, 24, 28, 32],
                                    supportAllValues: true
                                },

                                table: {
                                    contentToolbar: [
                                        'tableColumn',
                                        'tableRow',
                                        'mergeTableCells',
                                        'tableCellProperties',
                                        'tableProperties'
                                    ]
                                },

                                htmlSupport: {
                                    allow: [
                                        {
                                            name: /.*/,
                                            attributes: true,
                                            classes: true,
                                            styles: true
                                        }
                                    ]
                                }
                            })
                            .then(editor => {
                                // expose editor instance so external scripts can update its data
                                window.editorInstance = editor;
                            })
                            .catch(error => {
                                console.error(error);
                            });
                        </script>
                        @endsection
