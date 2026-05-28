@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Pengajuan Dokumen" />


     <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto">
                <table id="tableDocument" class="table-fixed min-w-full divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Judul</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Jenis Dokumen</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Status</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Start</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">End</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Document</th>
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
                                <td class="px-4 sm:px-6 py-3.5 text-center">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($d->template)->document_type ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ match($d->status) {'draft' => 'bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
                                    'submitted' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                                    'approved' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400',
                                    'published' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                    'aktif' => 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400',
                                    'selesai' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400',
                                    'denied' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                    'akan_expired' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
                                    'expired' => 'bg-gray-100 text-gray-500 dark:bg-gray-600/20 dark:text-gray-400',
                                    } }}">{{ $d->status }}</span>
                                </td>
                                
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $d->start_date ??'' }} </p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $d->end_date ??'' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 text-center">
                                    @if($d->source === 'upload' || $d->content_html)
                                        <a href="{{ route('documents.pdf', $d->id) }}" class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400" title="Open Document"><i class="fa-solid fa-file-pdf ml-1"></i></a>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </td>

                                <td class="px-4 sm:px-6 py-3.5">
                                    <form action="{{ route('documents.status', $d->id) }}" method="POST" onsubmit="return confirm('Ubah status dokumen ini?');">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-center gap-2">
                                            <button type="submit" name="status" value="denied" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-3 py-2 text-red-700 hover:bg-red-100" title="Tolak dokumen">
                                                <i class="fa-regular fa-circle-xmark"></i>
                                            </button>
                                            <button type="submit" name="status" value="approved" class="inline-flex items-center justify-center rounded-full border border-green-200 bg-green-50 px-3 py-2 text-green-700 hover:bg-green-100" title="Setujui dokumen">
                                                <i class="fa-regular fa-circle-check"></i>
                                            </button>
                                        </div>
                                    </form>
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
            const table = new DataTable('#tableDocument', {
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

            const searchInput = document.querySelector('#tableDocument_filter input');
            if (searchInput) searchInput.classList.add('rounded-md', 'border', 'px-3', 'py-2');
            const lengthSelect = document.querySelector('#tableDocument_length select');
            if (lengthSelect) lengthSelect.classList.add('rounded-md', 'border', 'px-2', 'py-1');

            const observer = new MutationObserver(() => {
                document.querySelectorAll('#tableDocument_paginate button').forEach(btn => btn.classList.add('rounded-md', 'border', 'px-2', 'py-1', 'mx-1'));
            });
            const paginateElem = document.querySelector('#tableDocument_paginate');
            if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
        });
    </script>
@endsection