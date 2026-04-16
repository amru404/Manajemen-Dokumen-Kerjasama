@props(['documents' => []])

@php
    $defaultDocuments = [
        [
            'title' => 'Dokumen Kerjasama A',
            'type' => 'MoU',
            'created_at' => '2024-01-15',
            'status' => 'Aktif',
        ],
        [
            'title' => 'Dokumen Kerjasama B',
            'type' => 'PKS',
            'created_at' => '2024-01-10',
            'status' => 'Draft',
        ],
        [
            'title' => 'Dokumen Kerjasama C',
            'type' => 'Berita Acara',
            'created_at' => '2024-01-05',
            'status' => 'Selesai',
        ],
    ];

    $documentsList = !empty($documents) ? $documents : $defaultDocuments;

    // Helper function for status classes
    $getStatusClasses = function($status) {
        $baseClasses = 'rounded-full px-2 py-0.5 text-theme-xs font-medium';

        return match($status) {
            'Aktif', 'Selesai' => $baseClasses . ' bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
            'Draft' => $baseClasses . ' bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
            'Dibatalkan' => $baseClasses . ' bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
            default => $baseClasses . ' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
        };
    };
@endphp

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Dokumen Terbaru</h3>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('judul-kerjasama') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                See all
            </a>
        </div>
    </div>

    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <table class="min-w-full">
            <thead>
                <tr class="border-t border-gray-100 dark:border-gray-800">
                    <th class="py-3 text-left">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Judul Dokumen</p>
                    </th>
                    <th class="py-3 text-left">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tipe</p>
                    </th>
                      <th class="py-3 text-left">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Mitra</p>
                    </th>
                    <th class="py-3 text-left">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal Dibuat</p>
                    </th>
                    <th class="py-3 text-left">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($documentsList as $document)
                    <tr class="border-t border-gray-100 dark:border-gray-800">
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-[40px] w-[40px] overflow-hidden rounded-md bg-gray-100 flex items-center justify-center">
                                    <i class="fa-regular fa-file text-gray-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                        @if(isset($document->judul))
                                            {{ $document->judul->judul ?? 'N/A' }}
                                        @else
                                            {{ $document['title'] ?? 'N/A' }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                @if(isset($document->template))
                                    {{ $document->template->document_type ?? 'N/A' }}
                                @else
                                    {{ $document['type'] ?? 'N/A' }}
                                @endif
                            </p>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                @if(isset($document->pihak1))
                                    {{ $document->pihak1->nama ?? 'N/A' }}
                                @else
                                    {{ $document['pihak1'] ?? 'N/A' }}
                                @endif
                            </p>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                @if(isset($document->created_at))
                                    {{ $document->created_at->format('d M Y') }}
                                @else
                                    {{ $document['created_at'] ?? 'N/A' }}
                                @endif
                            </p>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <span class=" text-sm p-1 {{ $document->status == 'published' ? 'bg-green-100 text-green-800' : ($document->status =='final' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')}}">
                                @if(isset($document->status))
                                    {{ $document->status }}
                                @else
                                    {{ $document['status'] ?? 'Aktif' }}
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>