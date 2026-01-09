@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Edit Template" />

    <div class="space-y-6 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function initEditor() {
                var textarea = document.querySelector('#template_format');
                if (!textarea) return;

                if (typeof CKEDITOR !== 'undefined') {
                    try {
                        if (CKEDITOR.instances['template_format']) {
                            CKEDITOR.instances['template_format'].destroy(true);
                        }
                        CKEDITOR.replace('template_format', {
                            height: 500,
                            extraPlugins: 'colorbutton,font,justify,pastefromword,table,tableresize',
                            toolbar: [
                                { name: 'clipboard', items: ['Undo','Redo','-','PasteFromWord','PasteText','-','Cut','Copy'] },
                                { name: 'styles', items: ['Format','Font','FontSize'] },
                                { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','Subscript','Superscript','RemoveFormat'] },
                                { name: 'colors', items: ['TextColor','BGColor'] },
                                { name: 'paragraph', items: ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
                                { name: 'links', items: ['Link','Unlink','Anchor'] },
                                { name: 'insert', items: ['Image','Table','HorizontalRule','SpecialChar'] },
                                { name: 'tools', items: ['Maximize','ShowBlocks'] },
                                { name: 'editing', items: ['Scayt'] }
                            ],
                            allowedContent: true
                        });
                    } catch (e) {
                        console.error('CKEditor init error:', e);
                        showEditorError(textarea);
                    }
                } else {
                    // Try to load CKEditor dynamically
                    console.warn('CKEditor not found, loading from CDN...');
                    var s = document.createElement('script');
                    s.src = 'https://cdn.ckeditor.com/4.25.1-lts/full-all/ckeditor.js';
                    s.onload = function () { initEditor(); };
                    s.onerror = function () { console.error('Failed to load CKEditor from CDN.'); showEditorError(textarea); };
                    document.head.appendChild(s);
                }
            }

            function showEditorError(textarea) {
                if (!textarea) return;
                var notice = document.createElement('div');
                notice.className = 'text-red-500 mt-2';
                notice.innerText = 'Editor gagal dimuat. Periksa koneksi atau console untuk info lebih lanjut.';
                if (!textarea.parentNode.querySelector('.editor-error')) {
                    notice.classList.add('editor-error');
                    textarea.parentNode.appendChild(notice);
                }
            }

            initEditor();
        });
    </script>

@endsection
