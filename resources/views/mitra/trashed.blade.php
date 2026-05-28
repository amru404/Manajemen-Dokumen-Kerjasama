@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Mitra Terhapus"/>
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <a href="{{ route('mitra') }}" class="bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2">Back to Mitra</a>
    </div>
    <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto">
                <table id="tableMitraTrashed" class="table-fixed min-w-full divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start text-gray-700 dark:text-gray-400">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Nama</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Penanggung Jawab</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Jabatan</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Email</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No Telepon</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Deleted At</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mitra as $m)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <span class="block font-medium text-gray-700 break-words text-theme-sm dark:text-gray-400">{{ $loop->iteration }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->nama }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->penanggung_jawab }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->jabatan }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->email }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->no_telp }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->deleted_at }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('mitra.restore', $m->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-full border border-green-200 bg-green-50 px-3 py-2 text-green-700 hover:bg-green-100" title="Restore">
                                                <i class="fa-solid fa-trash-arrow-up"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('mitra.force-delete', $m->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus permanen mitra ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-full border border-red-200 bg-red-50 px-3 py-2 text-red-700 hover:bg-red-100" title="Delete Permanently">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = new DataTable('#tableMitraTrashed', {
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

            const searchInput = document.querySelector('#tableMitraTrashed_filter input');
            if (searchInput) searchInput.classList.add('rounded-md', 'border', 'px-3', 'py-2');
            const lengthSelect = document.querySelector('#tableMitraTrashed_length select');
            if (lengthSelect) lengthSelect.classList.add('rounded-md', 'border', 'px-2', 'py-1');

            const observer = new MutationObserver(() => {
                document.querySelectorAll('#tableMitraTrashed_paginate button').forEach(btn => btn.classList.add('rounded-md', 'border', 'px-2', 'py-1', 'mx-1'));
            });
            const paginateElem = document.querySelector('#tableMitraTrashed_paginate');
            if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
        });
    </script>
@endsection
