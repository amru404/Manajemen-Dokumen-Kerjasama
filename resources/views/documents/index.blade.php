@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Dokumen - {{ $type }}" />

    <a href="{{ route('documents.' . $slug . '.create') }}" class="mb-4 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2">Add Document</a>

    <div class="space-y-6 md:space-y-7 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto">
                <table id="tableDocuments" class="table-fixed min-w-full divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start text-center">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Judul</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Mitra Pihak 1</th>
                             <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Mitra Pihak 2</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Start</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">End</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Document</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Send Email</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Status</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $d)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <span class="block font-medium text-gray-700 break-words text-theme-sm dark:text-gray-400">{{ $loop->iteration + ($documents->currentPage()-1) * $documents->perPage() }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($d->judul)->judul ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($pihak1)->nama ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($pihak2)->nama ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $d->start_date ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $d->end_date ?? '—' }}</p>
                                </td>
                                
                                <td class="px-4 sm:px-6 py-3.5 text-center">
                                    @if($d->source === 'upload' || $d->content_html)
                                        <a href="{{ route('documents.pdf', $d->id) }}" class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400" title="Open Document"><i class="fa-solid fa-file-pdf ml-1"></i></a>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <a href="{{ route('documents.send-email', $d->id) }}" class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400" title="Send Email"><i class="fa-solid fa-paper-plane ml-1"></i></a>
                                </td>

                                <td class="px-4 sm:px-6 py-3.5 text-center">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $d->status == 'published' ? 'bg-green-100 text-green-800' : ($d->status=='final' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">{{ $d->status }}</span>
                                </td>
                              
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('documents.show', $d->id) }}" class="flex justify-center text-gray-700 size-5 hover:text-indigo-500" title="View">
                                            <svg class="inline h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('documents.edit', $d->id) }}" class="flex justify-center text-gray-700 size-5 hover:text-green-500" title="Edit">
                                            <svg class="inline h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>

                                        <form action="{{ route('documents.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex justify-center text-gray-700 size-5 hover:text-red-500" title="Delete">
                                                <svg class="inline h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0V5a2 2 0 00-2-2h-3.5"/></svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $documents->links() }}
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
                dom: "<'flex items-center justify-between mb-4'lfr>t<'mt-4 flex items-center justify-between'ip>",
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