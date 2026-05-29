@extends('/layouts.app')

@php
$getStatusClassesActivity = function ($status) {
    $baseClasses = 'rounded-full px-2 py-0.5 text-theme-xs font-medium';

    return match($status) {
        'draft' => $baseClasses.' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
        'submitted' => $baseClasses.' bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
        'approved' => $baseClasses.' bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400',
        'published' => $baseClasses.' bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
        'created' => $baseClasses.' bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400',
        'selesai' => $baseClasses.' bg-emerald-50 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400',
        'denied' => $baseClasses.' bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
        'dibatalkan' => $baseClasses.' bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500',
        'akan_expired' => $baseClasses.' bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
        'expired' => $baseClasses.' bg-gray-100 text-gray-500 dark:bg-gray-600/20 dark:text-gray-400',
        default => $baseClasses.' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400'
    };
};

@endphp

@section('content') 
    <x-common.page-breadcrumb pageTitle="Document Activities" />
        
<x-common.page - breadcrumb pageTitle = "Add Data Mitra" /> <div class="space-y-6 md:space-y-7 mt-6">
    <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">

        <div class="max-w-full overflow-x-auto">
            <table id="tableDocuments" class="min-w-full whitespace-normal divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start text-center"
                id="tableDocuments"
                class="table-fixed min-w-full divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start">
                <thead
                    class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                    <tr>
                        <th
                            class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No</th>
                        <th
                            class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Judul Dokumen</th>
                        <th
                            class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Tipe</th>
                        <th
                            class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Desc</th>
                        <th
                            class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Waktu</th>
                        <th
                            class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Dibuat Oleh</th>
                        <th
                            class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $a)
                    <tr class="border-b border-gray_100 dark:border-white/[0.05]">
                        <td class="px_4 sm:px_6 py-3.5">
                            <span
                                class="block font medium text-gray_700 break-words text-theme-sm dark:text-gray-400">{{ $loop->iteration + ($activities->currentPage()-1) * $activities->perPage() }}</span>
                        </td>

                        <td class="px_4 sm:px_6 py-3.5">
                            <p
                                class="text-gray_700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($a->document)->judul->judul ?? '—' }}</p>
                        </td>
                        <td class="px_4 sm:px_6 py-3.5 text-center">
                            <p
                                class="text-gray_700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($a->document->template)->document_type ?? '—' }}</p>
                        </td>
                        <td class="px_4 sm:px_6 py-3.5">
                            <p
                            class="text-gray_700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $a->description ?? '—' }}</p>
                        </td>
                        <td class="px_4 sm:px_6 py-3.5">
                            <p
                                class="text-gray_700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $a->created_at->format('d M Y,H:i') }}</p>
                        </td>
                        <td class="px_4 sm:px_6 py-3.5">
                            <p
                                class="text-gray_700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($a->user)->name ?? '—' }}</p>
                        </td>
                        <td class="px_4 sm:px_6 py-3.5">
                            <span
                                class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $getStatusClassesActivity($a->activity_type) }}">
                                {{ $a->activity_type }}
                            </span>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>

 <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = new DataTable('#tableDocuments', {
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: -1 }],
                dom: "<'flex flex-wrap items-center gap-3 justify-between mb-4 dark:text-gray-200 dark:border-gray-200'<'flex flex-wrap items-center gap-3 dark:text-gray-200 dark:border-gray-200'l><'flex flex-wrap items-center gap-3 dark:text-gray-200 dark:border-gray-200'f>><'w-full overflow-x-auto dark:text-gray-200 dark:border-gray-200't><'mt-4 flex items-center justify-between dark:text-gray-200 dark:border-gray-200'ip >",
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    paginate: { previous: "Sebelumnya", next: "Berikutnya" }
                }
            });

            const searchInput = document.querySelector('#tableDocuments_filter input');
            if (searchInput) searchInput.classList.add('rounded-md', 'border', 'px-3', 'py-2');
            const lengthSelect = document.querySelector('#tableDocuments_length select');
            if (lengthSelect) lengthSelect.classList.add('rounded-md', 'border', 'px-2', 'py-1');

            const observer = new MutationObserver(() => {
                document.querySelectorAll('#tableDocuments_paginate button').forEach(btn => btn.classList.add('rounded-md', 'border', 'px-2', 'py-1', 'mx-1'));
            });
            const paginateElem = document.querySelector('#tableDocuments_paginate');
            if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
        });
    </script>
@endsection


