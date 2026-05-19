@extends('/layouts.app')

@section('content')

    <x-common.page-breadcrumb pageTitle="Edit Document - {{ optional($document->judul)->judul ?? 'Document' }}" />

    <div class="space-y-6 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <form action="{{ route('documents.update', $document->id) }}" method="POST">
                @csrf
                @method('PUT' )

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Judul Kerjasama</label>
                    <select name="judul_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                        <option value="">-- pilih judul --</option>
                        @foreach($juduls as $j)
                            <option value="{{ $j->id }}" {{ old('judul_id', $document->judul_id) == $j->id ? 'selected' : '' }}>{{ $j->judul }}</option>
                        @endforeach
                    </select>
                    @error('judul_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>


                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Template</label>
                    <select id="template_select" name="template_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                        <option value="">-- pilih template --</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" {{ $document->template_id == $tpl->id ? 'selected' : '' }}>{{ $tpl->name }}</option>
                        @endforeach
                    </select>
                    @error('template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Pihak Pertama</label>
                    <select name="pihak_1_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                        <option value="">-- pilih pihak 1 --</option>
                        @foreach($mitras as $m)
                            <option value="{{ $m->id }}" {{ $document->pihak_1_id == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Pihak Kedua</label>
                    <select name="pihak_2_id" class="mt-1 block w-full rounded-md border px-3 py-2">
                        <option value="">-- pilih pihak 2 --</option>
                        @foreach($mitras as $m)
                            <option value="{{ $m->id }}" {{ $document->pihak_2_id == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Isi Dokumen</label>
                    <textarea id="document_content" name="content_html" class="mt-1 block w-full rounded-md border px-3 py-2" rows="12">{{ old('content_html', $document->content_html) }}</textarea>
                    @error('content_html') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border px-3 py-2" required>
                        <option value="draft" {{ $document->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="final" {{ $document->status == 'final' ? 'selected' : '' }}>Final</option>
                        <option value="published" {{ $document->status == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">Update</button>
                    <a href="{{ route('documents.show', $document->id) }}" class="inline-block border px-4 py-2 rounded">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    
    <script>
        const _templates = @json($templates->mapWithKeys(fn($t) => [$t->id => $t->content_html]));

            initEditor();
            
            const select = document.querySelector('#template_select');
            if (select) {
                select.addEventListener('change', function () {
                    const id = this.value;
                    const html = _templates[id] ?? '';
                    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances['document_content']) {
                        CKEDITOR.instances['document_content'].setData(html);
                    } else {
                        document.querySelector('#document_content').value = html;
                    }
                });
            }
        });
    </script>

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