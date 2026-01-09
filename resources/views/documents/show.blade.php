@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Document Detail - {{ optional($document->judul)->judul ?? 'Document' }}" />

    <div class="space-y-6 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="mb-4">
                <h3 class="text-lg font-semibold">{{ optional($document->judul)->judul ?? 'Document' }}</h3>
                <p class="text-sm text-gray-500">Tipe: {{ optional($document->template)->document_type }} | Dibuat oleh: {{ optional($document->user)->name }}</p>
                <p class="text-sm text-gray-500">Judul Kerjasama: {{ optional($document->judul)->judul ?? '—' }}</p>
            </div>

            <div class="mb-4">
                <h4 class="font-medium">Template</h4>
                <p class="text-gray-700">{{ optional($document->template)->name ?? '—' }}</p>
            </div>

            <div class="mb-4">
                <h4 class="font-medium">Pihak Ke 1</h4>
                <p class="text-gray-700">{{ optional($document->pihak1)->nama ?? '—' }}</p>
            </div>

            
            <div class="mb-4">
                <h4 class="font-medium">Pihak Ke 2</h4>
                <p class="text-gray-700">{{ optional($document->pihak2)->nama ?? '—' }}</p>
            </div>

            <div class="mb-4">
                <h4 class="font-medium">Template Format</h4>
                <div class="prose max-w-none mt-2">
                    {{ $document->template->document_type }} - {{ $document->template->name }}
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <a href="{{ route('documents.edit', $document->id) }}" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">Edit</a>
            </div>

        </div>
    </div>

@endsection