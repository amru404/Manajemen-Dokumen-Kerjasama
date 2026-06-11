@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Document Detail - {{ optional($document->judul)->judul ?? 'Document' }}" />

    <div class="space-y-6 mt-4">
        <!-- Document Info -->
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-700 dark:bg-white/[0.03]">
            <div class="mb-4">
                <h3 class="text-lg font-semibold">{{ optional($document->judul)->judul ?? 'Document' }}</h3>
                <p class="text-sm text-gray-500">
                    Tipe: {{ optional($document->template)->document_type ?? '—' }} |
                    Dibuat oleh: {{ optional($document->user)->name ?? '—' }} |
                    Status: <span class="px-2 py-1 text-xs rounded-full
                        @if($document->status === 'draft') bg-yellow-100 text-yellow-800
                        @elseif($document->status === 'final') bg-blue-100 text-blue-800
                        @elseif($document->status === 'published') bg-green-100 text-green-800
                        @endif">{{ ucfirst($document->status) }}</span>
                </p>
                <p class="text-sm text-gray-500">Nomor Dokumen: {{ $document->nomor_document ?? '—' }}</p>
                <p class="text-sm text-gray-500">Sumber: {{ $document->source === 'generate' ? 'Dibuat dari Form' : ' Upload File' }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <h4 class="font-medium">Template</h4>
                    <p class="text-gray-700">{{ optional($document->template)->name ?? '—' }}</p>
                </div>

                <div>
                    <h4 class="font-medium">Judul Kerjasama</h4>
                    <p class="text-gray-700">{{ optional($document->judul)->judul ?? '—' }}</p>
                </div>

                <div>
                    <h4 class="font-medium">Pihak Ke 1</h4>
                    <p class="text-gray-700">{{ optional($document->pihak1)->nama ?? '—' }}</p>
                </div>

                <div>
                    <h4 class="font-medium">Pihak Ke 2</h4>
                    <p class="text-gray-700">{{ optional($document->pihak2)->nama ?? '—' }}</p>
                </div>

                
                <div>
                    <h4 class="font-medium">Email Pihak 1 </h4>
                    <p class="text-gray-700">{{ optional($document->pihak1)->email ?? '—' }}</p>
                </div>

                <div>
                    <h4 class="font-medium">Email Pihak 2 </h4>
                    <p class="text-gray-700">{{ optional($document->pihak2)->email ?? '—' }}</p>
                </div>

                <div>
                    <h4 class="font-medium">Tanggal Mulai</h4>
                    <p class="text-gray-700">{{ $document->start_date ? \Carbon\Carbon::parse($document->start_date)->format('d M Y') : '—' }}</p>
                </div>

                <div>
                    <h4 class="font-medium">Tanggal Berakhir</h4>
                    <p class="text-gray-700">{{ $document->end_date ? \Carbon\Carbon::parse($document->end_date)->format('d M Y') : '—' }}</p>
                </div>
            </div>
            </div>
        </div>

        <!-- Document Preview -->
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-700 dark:bg-white/[0.03] mt-3">
            <h4 class="text-lg font-semibold mb-4">Preview Dokumen</h4>

            @if($document->source === 'generate' && $document->content_html)
                <!-- Preview untuk dokumen yang dibuat dari form -->
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="prose max-w-none">
                        {!! $document->content_html !!}
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">💡 Preview HTML - Klik "Lihat PDF" untuk versi PDF</p>

            @elseif($document->source === 'upload' && $document->file_path)
                <!-- Preview untuk dokumen yang diupload -->
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="text-center py-8">
                        <div class="text-6xl mb-4">📄</div>
                        <p class="text-gray-600 mb-2">File PDF telah diupload</p>
                        <p class="text-sm text-gray-500">{{ basename($document->file_path) }}</p>
                        <p class="text-xs text-gray-500 mt-2">💡 Klik "Lihat PDF" untuk melihat isi dokumen</p>
                    </div>
                </div>

            @else
                <!-- Fallback jika tidak ada konten -->
                <div class="border rounded-lg p-4 bg-red-50 text-center">
                    <div class="text-red-500">
                        <div class="text-4xl mb-2">⚠️</div>
                        <p>Tidak ada konten untuk ditampilkan</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
