@extends('/layouts.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Edit Document - {{ optional($document->judul)->judul ?? 'Document' }}" />

    <div class="space-y-6 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            
            <!-- Alpine.js Container -->
            <div x-data="documentEditForm()" class="space-y-6">

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
                <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data"
                    @submit="onSubmit">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="mode" :value="activeTab">

                    <!-- Common Fields (tidak dalam tabs) -->
                    <div class="space-y-4 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Judul Kerjasama</label>
                            <select name="judul_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                                <option value=""> pilih judul </option>
                                @foreach($juduls as $j)
                                    <option value="{{ $j->id }}" {{ old('judul_id', $document->judul_id) == $j->id ? 'selected' : '' }}>{{ $j->judul }}</option>
                                @endforeach
                            </select>
                            @error('judul_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Template</label>
                            <select id="template_select" name="template_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                                <option value=""> pilih template </option>
                                @foreach($templates as $tpl)
                                    <option value="{{ $tpl->id }}" {{ $document->template_id == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
                                @endforeach
                            </select>
                            @error('template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pihak Pertama</label>
                                <select name="pihak_1_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                                    <option value=""> pilih pihak 1 </option>
                                    @foreach($mitras as $m)
                                        <option value="{{ $m->id }}" {{ $document->pihak_1_id == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pihak Kedua</label>
                                <select name="pihak_2_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                                    <option value=""> pilih pihak 2 </option>
                                    @foreach($mitras as $m)
                                        <option value="{{ $m->id }}" {{ $document->pihak_2_id == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="mt-1 block w-full rounded-md border px-3 py-2"
                                    value="{{ old('start_date', $document->start_date) }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Berakhir</label>
                                <input type="date" name="end_date" class="mt-1 block w-full rounded-md border px-3 py-2"
                                    value="{{ old('end_date', $document->end_date) }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full rounded-md border px-3 py-2" required>
                                <option value="denied" {{ $document->status == 'denied' ? 'selected' : '' }}>Denied</option>
                                <option value="draft" {{ $document->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ $document->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                <option value="approved" {{ $document->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="published" {{ $document->status == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                    </div>

                    <!-- TAB 1: Form Editor -->
                    <div x-show="activeTab === 'form'" class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Isi Dokumen</h3>

                        <div>
                            <textarea id="document_content" name="content_html" class="mt-1 block w-full rounded-md border px-3 py-2" rows="12">{{ old('content_html', $document->content_html) }}</textarea>
                            @error('content_html') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- TAB 2: Upload File -->
                    <div x-show="activeTab === 'upload'" class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900">Upload File PDF</h3>
                        
                        @if($document->file_path)
                            <div class="rounded-lg bg-blue-50 p-4 border border-blue-200 dark:bg-blue-900/20 dark:border-blue-800">
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    <strong>File Saat Ini:</strong> {{ basename($document->file_path) }}
                                </p>
                                <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                                    Biarkan kosong jika tidak ingin mengganti file
                                </p>
                            </div>
                        @endif

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih File PDF</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-indigo-500 transition-colors"
                                @click="$refs.fileInput.click()"
                                @drop.prevent="handleDrop"
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                :class="isDragging ? 'border-indigo-500 bg-indigo-50' : ''">
                                
                                <input type="file" name="pdf_file" accept=".pdf" class="hidden" @change="handleFileSelect" x-ref="fileInput">
                                
                                <div x-show="!selectedFile">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v24a4 4 0 004 4h24a4 4 0 004-4V20m-12-8v12m-8-4h16M8 20h32M8 28h32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="mt-4 text-sm font-medium text-gray-700">Klik untuk pilih file atau drag & drop</p>
                                    <p class="text-xs text-gray-500 mt-1">Format PDF, max 10MB</p>
                                </div>

                                <div x-show="selectedFile">
                                    <svg class="mx-auto h-12 w-12 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="mt-4 text-sm font-medium text-green-700" x-text="selectedFile.name"></p>
                                    <p class="text-xs text-gray-500 mt-1" x-text="formatFileSize(selectedFile.size)"></p>
                                </div>
                            </div>
                            @error('pdf_file') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-2 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">Update</button>
                        <a href="{{ route('documents.show', $document->id) }}" class="inline-block border px-4 py-2 rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    
    <script>
        function documentEditForm() {
            return {
                activeTab: '{{ $document->source === 'upload' ? 'upload' : 'form' }}',
                selectedFile: null,
                isDragging: false,
                _templates: @json($templates->mapWithKeys(fn($t) => [$t->id => $t->content_html])),
                editorInitialized: false,
                
                init() {
                    // Setup watcher untuk activeTab changes
                    this.$watch('activeTab', (newTab) => {
                        if (newTab === 'form') {
                            this.$nextTick(() => this.initEditor());
                        }
                    });

                    // Initialize editor langsung jika activeTab sudah 'form'
                    if (this.activeTab === 'form') {
                        this.$nextTick(() => this.initEditor());
                    }
                },

                initEditor() {
                    // Hindari double initialization
                    if (this.editorInitialized && window.editorInstance) {
                        return;
                    }

                    const textarea = document.querySelector('#document_content');
                    if (!textarea) return;

                    ClassicEditor.create(textarea, {
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
                        window.editorInstance = editor;
                        this.editorInitialized = true;
                    })
                    .catch(error => {
                        console.error('CKEditor init error:', error);
                    });
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file && file.type === 'application/pdf') {
                        this.selectedFile = file;
                    } else {
                        alert('Hanya file PDF yang diperbolehkan');
                        this.selectedFile = null;
                        event.target.value = '';
                    }
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const files = event.dataTransfer.files;
                    if (files.length > 0) {
                        this.$refs.fileInput.files = files;
                        this.handleFileSelect({ target: { files } });
                    }
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                },

                onSubmit() {
                    // Validasi berdasarkan tab yang aktif
                    if (this.activeTab === 'form') {
                        // Form editor mode - ensure content_html is filled
                        if (!window.editorInstance || !window.editorInstance.getData().trim()) {
                            alert('Konten dokumen tidak boleh kosong');
                            return false;
                        }
                    } else if (this.activeTab === 'upload') {
                        // Upload mode - file optional (untuk keep file lama)
                        // No validation needed - file is optional
                    }
                    return true;
                }
            };
        }

        // Initialize template change listener
        const select = document.querySelector('#template_select');
        if (select) {
            select.addEventListener('change', function () {
                const templates = @json($templates->mapWithKeys(fn($t) => [$t->id => $t->content_html]));
                const id = this.value;
                const html = templates[id] ?? '';
                if (window.editorInstance) {
                    window.editorInstance.setData(html);
                } else {
                    document.querySelector('#document_content').value = html;
                }
            });
        }
    </script>

@endsection