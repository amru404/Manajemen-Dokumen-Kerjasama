@extends('/layouts.app')

@section('content')
        <style>
            /* editor.css */
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            line-height: 1.8;
            padding: 20px;
            color: #000;
        }

        h1, h2, h3 {
            margin-top: 18px;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 12px;
        }

        .pasal {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
        }

        .signature td {
            padding-top: 20px;
        }

        </style>
    <x-common.page-breadcrumb pageTitle="Edit Template" />

    <div class="space-y-6 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-700 dark:bg-white/[0.03]">
            <form action="{{ route('templates.update', $template->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name', $template->name) }}" class="mt-1 block w-full rounded-md border px-3 py-2" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Document Type</label>
                    <select name="document_type" class="mt-1 block w-full rounded-md border px-3 py-2" required>
                        <option value="">Pilih tipe</option>
                        <option value="MoU" {{ old('document_type', $template->document_type) == 'MoU' ? 'selected' : '' }}>MoU</option>
                        <option value="PKS" {{ old('document_type', $template->document_type) == 'PKS' ? 'selected' : '' }}>PKS</option>
                        <option value="Berita Acara" {{ old('document_type', $template->document_type) == 'Berita Acara' ? 'selected' : '' }}>Berita Acara</option>
                    </select>
                    @error('document_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" class="mt-1 block w-full rounded-md border px-3 py-2" rows="3">{{ old('description', $template->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Template Format</label>
                    <textarea id="template_format" name="content_html" class="mt-1 block w-full rounded-md border px-3 py-2" rows="8">{{ old('content_html', $template->content_html) }}</textarea>
                    @error('content_html') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">Update</button>
                    <a href="{{ route('templates.index') }}" class="inline-block border px-4 py-2 rounded">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.querySelector('#template_format'), {
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
        .catch(error => {
            console.error('CKEditor init error:', error);
            var textarea = document.querySelector('#template_format');
            if (textarea && !textarea.parentNode.querySelector('.editor-error')) {
                var notice = document.createElement('div');
                notice.className = 'text-red-500 mt-2 editor-error';
                notice.innerText = 'Editor gagal dimuat. Periksa koneksi atau console untuk info lebih lanjut.';
                textarea.parentNode.appendChild(notice);
            }
        });
    </script>

@endsection

